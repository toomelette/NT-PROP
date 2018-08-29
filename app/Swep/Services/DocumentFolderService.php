<?php
 
namespace App\Swep\Services;


use App\Swep\Interfaces\DocumentFolderInterface;
use App\Swep\BaseClasses\BaseService;



class DocumentFolderService extends BaseService{



    protected $doc_folder_repo;



    public function __construct(DocumentFolderInterface $doc_folder_repo){

        $this->doc_folder_repo = $doc_folder_repo;
        parent::__construct();

    }





    public function fetchAll($request){

        $doc_folders = $this->doc_folder_repo->fetchAll($request);

        $request->flash();
        return view('dashboard.document_folder.index')->with('doc_folders', $doc_folders);

    }






    public function store($request){

    	$doc_folder = $this->doc_folder_repo->store($request);

        $this->event->fire('document_folder.store');
        return redirect()->back();

    }





    public function edit($slug){

    	$doc_folder = $this->doc_folder_repo->findBySlug($slug);
        return view('dashboard.document_folder.edit')->with('doc_folder', $doc_folder);

    }





    public function update($request, $slug){

    	$doc_folder = $this->doc_folder_repo->update($request, $slug);

        $this->event->fire('document_folder.update', $doc_folder);
        return redirect()->route('dashboard.document_folder.index');

    }





    public function destroy($slug){

    	$doc_folder = $this->doc_folder_repo->destroy($slug);

        $this->event->fire('document_folder.destroy', $doc_folder);
        return redirect()->route('dashboard.document_folder.index');

    }







}