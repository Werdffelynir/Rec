<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Snippets;
use app\Models\Subcategory;
use rec\Controller;

class Records extends Controller
{
    public $auth = false;
    private $userId;
    private $userData;

    private $modelSnippets;
    private $modelCategory;
    private $modelSubcategory;

    public function __construct($userData)
    {
        parent::__construct();

        if($userData){
            $this->auth = true;
            $this->userId = $userData['id'];
            $this->userData = $userData;
        }

        $this->modelSnippets = new Snippets();
        $this->modelCategory = new Category();
        $this->modelSubcategory = new Subcategory();
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    public function publicSnippets()
    {

    }

    public function publicCategoryList()
    {
        return $this->modelCategory->db->getAll("*", "visibly=1 and type='public' and id_user=0 ORDER BY id asc");
    }

    public function publicSubcategory($category = null)
    {
/**/
        if (is_numeric($category))
            return $this->modelSubcategory->db->getAll("*", "visibly=1 AND type='public' AND id_category=".$category);
        else if(is_string($category))
            return $this->modelSubcategory->db->getByAttr('link',$category, null, " visibly=1AND type='public'");
        else
            return $this->modelSubcategory->db->getAll("*", "visibly=1 and type='public'");
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


    public function privateCategoryList()
    {
        if($this->auth)
            return $this->modelCategory->db->getAll("*", "visibly=1 AND (id_user=$this->userId OR id_user=0) ORDER BY id asc");
        else
            return [];
    }

    public function privateSubcategory($id = null)
    {
        if(is_numeric($id))
            return $this->modelSubcategory->db->getAll("*", "visibly=1 AND (id_user=$this->userId OR id_user=0) AND id=$id");
        else
            return $this->modelSubcategory->db->getAll("*", "visibly=1 AND (id_user=$this->userId OR id_user=0)");/**/
    }

    public function privateSnippets()
    {

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


    public function save($updateId=null)
    {
        $resultInsSnippet = null;
        $snippetLink = null;

        $idCategory = $this->post('id_category');
        $idSubCategory = $this->post('id_sub_category');
        $newCategory = $this->post('new_category');
        $newSubCategory = $this->post('new_sub_category');

        $data['tags'] = $this->post('tags');
        $data['type'] = 'private';
        $data['id_user'] = $this->userId;
        $data['title'] = $this->post('title');
        $data['content'] = $this->post('content');
        $data['link'] = $this->post('link');
        $data['datecreate'] = $this->post('datecreate');

        if($idCategory == 'new'){

            $categoryLink = $this->createLink($newCategory);
            $checkCatLink = $this->modelCategory->db->getByAttr('link',$categoryLink);
            if(!empty($checkCatLink))
                $categoryLink = $this->modelCategory->db->lastId().'_'.$categoryLink;

            $resultInsCat = $this->modelCategory->db->insert(
                [
                    'id_user'=>$data['id_user'],
                    'title'=>$newCategory,
                    'link'=>$categoryLink,
                    'type'=>'public',
                    'datecreate'=>date("d.m.Y H:i:s"),
                ]
            );
            $data['id_category'] = $resultInsCat;
        }else{
            $data['id_category'] = $idCategory;
        }

        if($idSubCategory == 'new'){

            $subCategoryLink = $this->createLink($newSubCategory);
            $checkSubCatLink = $this->modelSubcategory->db->getByAttr('link',$subCategoryLink);
            if(!empty($checkSubCatLink))
                $subCategoryLink = $this->modelSubcategory->db->lastId().'_'.$subCategoryLink;

            $resultInsSubCat = $this->modelSubcategory->db->insert(
                [
                    'id_category' => $data['id_category'],
                    'id_user' => $data['id_user'],
                    'title' => $newSubCategory,
                    'link' => $subCategoryLink,
                    'type' => 'public',
                    'datecreate' => date("d.m.Y H:i:s"),
                ]);
            $data['id_sub_category'] = $resultInsSubCat;
        }else{
            $data['id_sub_category'] = $idSubCategory;
        }

        if(is_numeric($data['id_category']) && is_numeric($data['id_sub_category']))
        {
            if(is_numeric($updateId)){
                $resultInsSnippet = $this->modelSnippets->db->update(
                    [
                        'id_category' => $data['id_category'],
                        'id_sub_category' => $data['id_sub_category'],
                        'id_user' => $data['id_user'],
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'tags' => $data['tags'],
                        'type' => $data['type'],
                        'datecreate' => $data['datecreate'],
                    ],
                    'id='.$updateId
                );
                $_updateRecord = $this->modelSnippets->db->getById($updateId);
                $snippetLink = $_updateRecord['link'];

            }else{

                $snippetLink = 's'.$this->modelSnippets->db->lastId().'_'.$this->createLink($data['title']);
                $resultInsSnippet = $this->modelSnippets->db->insert(
                    [
                        'id_category' => $data['id_category'],
                        'id_sub_category' => $data['id_sub_category'],
                        'id_user' => $data['id_user'],
                        'link' => $snippetLink,
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'tags' => $data['tags'],
                        'type' => $data['type'],
                        'datecreate' => date("d.m.Y H:i:s")
                    ]
                );
            }
        }

        if($resultInsSnippet) {
            $this->flash('save', 'Data saved successfully');
            $this->redirect('/space/edit/'.$snippetLink);
        } else {
            $this->flash('save', 'An error occurred when saving, data check the the form fields!');
            $this->flash('errorFields', serialize($data));
            $this->redirect('/space/edit/'.$snippetLink);
        }
    }

    public function createLink($string, $addRandom=0)
    {
        $stringUrl = trim( preg_replace(
            '/[^\w]/',
            '',
            strip_tags( htmlspecialchars_decode($string) ) ) );
        if(empty($stringUrl)){
            $stringUrl = $this->randomWord(16);
        }
        if($addRandom>0){
            $stringUrl .= $stringUrl.$this->randomWord($addRandom);
        }
        return strtolower($stringUrl);
    }

    public function randomWord($len = 6) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
}