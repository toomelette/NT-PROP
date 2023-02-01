<?php


namespace App\Http\Controllers;



use App\Http\Requests\Article\ArticleFormRequest;
use App\Models\Articles;
use App\Swep\Helpers\Helper;
use App\Swep\Services\ArticlesService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ArticlesController extends Controller
{
    protected $articleService;
    public function __construct(ArticlesService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.articles.index');
    }

    public function dataTable($request){
        $articles = Articles::query();
        return DataTables::of($articles)
            ->addColumn('action',function($data){
                return view('ppu.articles.dtActions')->with([
                    'data' => $data
                ]);
            })
            ->editColumn('unitPrice',function($data){
                return number_format($data->unitPrice,2);
            })
            ->editColumn('uom',function($data){
                return strtoupper($data->uom);
            })
            ->editColumn('type',function($data){
                return Helper::toSentence($data->type);
            })
            ->editColumn('modeOfProc',function($data){
                return Helper::toSentence($data->modeOfProc);
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function store(ArticleFormRequest $request){
        $a = new Articles();
        $a->acctCode = $request->acctCode;
        $a->stockNo = $this->articleService->makeStockNo();
        $a->article = $request->article;
        $a->modeOfProc = $request->modeOfProc;
        $a->type = $request->type;
        $a->unitPrice = Helper::sanitizeAutonum($request->unitPrice);
        $a->uom = $request->uom;
        if($a->save()){
            return $a->only('id');
        }
        abort(503,'Error saving article.');
    }

    public function edit($id){
        $a = $this->findById($id);
        return view('ppu.articles.edit')->with([
            'article' => $a,
        ]);
    }
    public function update(Request $request, $id){
        $a = $this->findById($id);
        $a->acctCode = $request->acctCode;
        $a->article = $request->article;
        $a->modeOfProc = $request->modeOfProc;
        $a->type = $request->type;
        $a->unitPrice = Helper::sanitizeAutonum($request->unitPrice);
        $a->uom = $request->uom;
        if($a->update()){
            return $a->only('id');
        }
        abort(503,'Error updating article.');
    }
    public function destroy($id){
        if($this->findById($id)->delete()){
            return 1;
        }
        abort(503,'Error deleting article.');
    }

    public function findById($id){
        $a = Articles::query()->find($id);
        return $a ?? abort(503,'Article not found.');
    }
}