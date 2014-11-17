<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Snippets;
use app\Models\Subcategory;
use rec\Controller;

class Edit extends Controller
{

    public $formData = [];

    public function __construct($type, $link)
    {
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

        switch($type){
            case 'create':
                $this->create();
                break;
            case 'update':
                $this->update($link);
                break;
            case 'delete':
                $this->delete($link);
                break;
            case 'insert':
                $this->insert();
                break;
        }
    }

    private function create(){

    }
    private function update($link){

    }
    private function delete($link){

    }
    private function insert()
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

        if($idCategory == 'new'){
            $catModel = new Category();
            $resultInsCat = $catModel->db->insert(
                ["id_user","title","link","type","datecreate"],
                [
                    'id_user'=>$data['id_user'],
                    'title'=>$newCategory,
                    'link'=>$this->createLink($newCategory),
                    'type'=>'public',
                    'datecreate'=>date('m.d.Y H:i:s'),
                ]);
            $data['id_category'] = $resultInsCat;
        }else{
            $data['id_category'] = $idCategory;
        }

        if($idSubCategory == 'new'){

            $subCatModel = new Subcategory();
            $resultInsSubCat = $subCatModel->db->insert(
                ["id_category", "id_user", "title", "link", "type", "datecreate"],
                [
                    'id_category' => $data['id_category'],
                    'id_user' => $data['id_user'],
                    'title' => $newSubCategory,
                    'link' => $this->createLink($newSubCategory),
                    'type' => 'public',
                    'datecreate' => date('m.d.Y H:i:s'),
                ]);
            $data['id_sub_category'] = $resultInsSubCat;
        }else{
            $data['id_sub_category'] = $idSubCategory;
        }

        if(is_numeric($data['id_category']) && is_numeric($data['id_sub_category'])){

            $snippetsModel = new Snippets();
            $snippetLink = $this->createLink($data['title']);
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
                    'type' => $data['id_user'],
                    'datecreate' => date('m.d.Y H:i:s')
                ]
            );
        }

        if($resultInsSnippet) {
            $this->redirect('edit/edit/'.$snippetLink);
        } else {
            $this->session('errorFields', serialize($data));
            $this->redirect('edit/error');
        }

    }

    public function createLink($string)
    {
        $stringUrl = trim( preg_replace(
            '/[^\w]/',
            '',
            strip_tags( htmlspecialchars_decode($string) ) ) );
        if(empty($stringUrl)){
            $stringUrl = $this->randomWord(16);
        }
        return strtolower($stringUrl);
    }

    public function randomWord($len = 6) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }



}