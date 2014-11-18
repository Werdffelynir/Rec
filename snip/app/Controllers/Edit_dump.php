<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Snippets;
use app\Models\Subcategory;
use rec\Controller;

class Edit_dump extends Controller
{

    public $formData = [];

    public function __construct($type, $link)
    {
        $modelSnippets = new Snippets();
        $modelCategory = new Category();
        $modelSubcategory = new Subcategory();

        $this->formData['categories'] = $modelCategory->db->getAll(null,"visibly=1 and type='public'");

        $subCat = [];
        $_subCat = $modelSubcategory->db->getAll(null,"visibly=1 and type='public'");
        foreach ($_subCat as $sc) {
            $_sc['id'] = $sc['id'];
            $_sc['title'] = $sc['title'];
            $subCat[$sc['id_category']][] = $_sc;
        }
        $this->formData['subcategories'] = json_encode($subCat);

        if(empty($link))
            $this->formData['snippet'] = $modelSnippets->fields;
        else
            $this->formData['snippet'] = $modelSnippets->db->getByAttr('link',$link);

        switch($type){
            case 'create':
                break;
            case 'update':
                break;
            case 'delete':
                $this->delete($link);
                break;
            case 'save':
                $this->save($link);
                break;
        }
    }

    private function update($link)
    {
        $modelSnippet = new Snippets();
        $this->formData['snippet'] = $modelSnippet->db->getByAttr('link',$link);
    }
    private function delete($link){

    }
    private function save($update_link=null)
    {
        $resultInsSnippet = null;
        $snippetLink = null;

        $idCategory = $this->post('id_category');
        $idSubCategory = $this->post('id_sub_category');
        $newCategory = $this->post('new_category');
        $newSubCategory = $this->post('new_sub_category');

        $data['tags'] = $this->post('tags');
        $data['type'] = $this->post('type');
        $data['id_user'] = $this->post('id_user');
        $data['title'] = $this->post('title');
        $data['content'] = $this->post('content');
        $data['link'] = $this->post('link');
        $data['ithelp'] = $this->post('ithelp');
        $data['datecreate'] = $this->post('datecreate');

        if($idCategory == 'new'){
            $catModel = new Category();

            // check lnk to exists
            $categoryLink = $this->createLink($newCategory);
            $checkLink = $catModel->db->getByAttr('link',$categoryLink);
            if(!empty($checkLink))
                $categoryLink = $this->createLink($checkLink,5);

            $resultInsCat = $catModel->db->insert(
                ["id_user","title","link","type","datecreate"],
                [
                    'id_user'=>$data['id_user'],
                    'title'=>$newCategory,
                    'link'=>$categoryLink,
                    'type'=>'public',
                    'datecreate'=>time(),
                ]);
            $data['id_category'] = $resultInsCat;
        }else{
            $data['id_category'] = $idCategory;
        }

        if($idSubCategory == 'new'){

            $subCatModel = new Subcategory();

            // check lnk to exists
            $subCategoryLink = $this->createLink($newSubCategory);
            $checkSubCatLink = $subCatModel->db->getByAttr('link',$subCategoryLink);
            if(!empty($checkSubCatLink))
                $subCategoryLink = $this->createLink($subCategoryLink,5);

            $resultInsSubCat = $subCatModel->db->insert(
                ["id_category", "id_user", "title", "link", "type", "datecreate"],
                [
                    'id_category' => $data['id_category'],
                    'id_user' => $data['id_user'],
                    'title' => $newSubCategory,
                    'link' => $subCategoryLink,
                    'type' => 'public',
                    'datecreate' => time(),
                ]);
            $data['id_sub_category'] = $resultInsSubCat;
        }else{
            $data['id_sub_category'] = $idSubCategory;
        }

        if(is_numeric($data['id_category']) && is_numeric($data['id_sub_category'])){

            $snippetsModel = new Snippets();

            // check lnk to exists
            $snippetLink = $this->createLink($data['title']);
            $checkSnipLink = $snippetsModel->db->getByAttr('link',$snippetLink);
            if(!empty($checkSnipLink))
                $snippetLink = $this->createLink($snippetLink,5);

            if(!empty($update_link)){
                $resultInsSnippet = $snippetsModel->db->update(
                    [
                        "id_category",
                        "id_sub_category",
                        "id_user",
                        "link",
                        "title",
                        "content",
                        "tags",
                        "type",
                        "datecreate"
                    ],
                    [
                        'id_category' => $data['id_category'],
                        'id_sub_category' => $data['id_sub_category'],
                        'id_user' => $data['id_user'],
                        'link' => $data['link'],
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'tags' => $data['tags'],
                        'type' => $data['type'],
                        'datecreate' => $data['datecreate'],
                    ],
                    'id='.(int)$update_link
                );
            }else{
                $resultInsSnippet = $snippetsModel->db->insert(
                    [
                        "id_category",
                        "id_sub_category",
                        "id_user",
                        "link",
                        "title",
                        "content",
                        "tags",
                        "type",
                        "datecreate"
                    ],
                    [
                        'id_category' => $data['id_category'],
                        'id_sub_category' => $data['id_sub_category'],
                        'id_user' => $data['id_user'],
                        'link' => $snippetLink,
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'tags' => $data['tags'],
                        'type' => $data['type'],
                        'datecreate' => time()
                    ]
                );
            }

        }

        if($resultInsSnippet) {
            $this->redirect('edit/update/'.$snippetLink);
        } else {
            $this->session('errorFields', serialize($data));
            $this->redirect('edit/error');
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