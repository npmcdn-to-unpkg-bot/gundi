<?php

namespace Module\Catalog\Component\Controller;

use Core\Library\Component\Controller;
use Core\Library\Error\Error;
use Module\Catalog\Model\Products as ModelProducts;
use Module\Catalog\Model\Categories as ModelCategories;
use Core\Contract\Resource\IAddable;
use Core\Contract\Resource\IEditable;
use Core\Contract\Resource\IDeleteable;
use Core\Contract\Resource\IShowable;
use Core\Library\Validator\Validator;
use Core\Library\Request\Request;

class Products extends Controller implements IAddable, IEditable, IShowable, IDeleteable
{
    private $_oModelProducts;
    private $_oModelCategories;
    private $_oRequest;
    private $_oError;
    private $_oValidator;
    public function __construct(
        ModelCategories $oModelCategories,
        ModelProducts $oModelProducts,
        Request $oRequest,
        Error $oError,
        Validator $oValidator
    )
    {
        $this->_oModelCategories = $oModelCategories;
        $this->_oModelProducts = $oModelProducts;
        $this->_oRequest = $oRequest;
        $this->_oError = $oError;
        $this->_oValidator = $oValidator;
    }

    /**
     * Get list of products
     * @return void
     */
    public function index()
    {
        $iPage = (int)$this->_oRequest->get('page') ?: 1;
        $iPerPage = (int)$this->_oRequest->get('per_page') ?: 30;
        $sSort = (!empty($this->_oRequest->get('sort')) && $this->_oRequest->get('sort') != 'false' ?$this->_oRequest->get('sort'): $this->_oModelProducts->getKeyName());
        $this->oView->assign('products', $this->_oModelProducts
            ->with('category')
            ->where(function($query){
                if (!empty($this->_oRequest->get('find'))) {
                    $query->where("name", "like", '%'.$this->_oRequest->get('find').'%');
                }
            })
            ->orderBy($sSort, 'asc')
            ->paginate($iPerPage, ['*'], 'products', $iPage));
    }

    /**
     * Show add form
     * @return void
     */
    public function add()
    {

        try{
            if (!empty($this->_oRequest->get('id'))){
                $this->oView->assign('product', $this->_oModelProducts->findOrFail($this->_oRequest->get('id')));
            }
            $this->oView->assign('categories', $this->_oModelCategories->all());
        }catch(\Exception $oException){
            $this->_oError->display($oException->getMessage(), 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Save to DB
     * @return void
     */
    public function create()
    {
        $aProduct = $this->_oRequest->get('product');
        $this->_oValidator->required('status');
        $this->_oValidator->required('category_id');
        $this->_oValidator->required('name');
        $this->_oValidator->required('price');
        if ($this->_oValidator->isValid($aProduct)) {
            try{
                if (($aAddedProduct = $this->_oModelProducts->create($aProduct))){
                    $this->oView->assign('response', ['message' => 'Product successfully added', 'product'=>$aAddedProduct]);
                }
            }catch (\Exception $oException){
                $this->_oError->display($oException->getMessage(), 406, $this->_oRequest->getExt());
            }
        }else{
            $this->_oError->display('Provide all fields and enter correct data', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Delete item from database
     * @param string|int $mID
     * @return void
     */
    public function delete($mID)
    {
        if (!$this->_oModelProducts->where($this->_oModelProducts->getKeyName(), $mID)->delete()){
            $this->_oError->display('Unable delete product from database', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Mass delete product
     * @return void
     */

    public function massDelete()
    {
        $aProducts = $this->_oRequest->get('products');
        if (!$this->_oModelProducts->whereIn($this->_oModelProducts->getKeyName(), $aProducts)->delete()){
            $this->_oError->display('Unable delete products from database', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Show edit form
     * @param string|int $mId
     */
    public function edit($mId)
    {
        try{
            $this->oView->assign('product', $this->_oModelProducts->findOrFail($mId)->first());
        }catch(\Exception $oException){
            $this->_oError->display('Product not found with id:'.$mId, 404, $this->_oRequest->getExt());
        }
    }

    /**
     * Update product
     * @param string|int $mId
     */
    public function update($mId)
    {
        $aProduct = Gundi()->Request->get('product');
        $this->_oValidator->required('status');
        $this->_oValidator->required('category_id');
        $this->_oValidator->required('name');
        $this->_oValidator->required('price');

        if ($this->_oValidator->isValid($aProduct)) {
            try {
                $aUpdated['status'] = $aProduct['status'];
                $aUpdated['category_id'] = $aProduct['category_id'];
                $aUpdated['name'] = $aProduct['name'];
                $aUpdated['price'] = $aProduct['price'];
                $this->_oModelProducts->where($this->_oModelProducts->getKeyName(),'=', $mId)->update($aUpdated);
            } catch (\Exception $oException) {
                $this->_oError->display($oException->getMessage(), 406, $this->_oRequest->getExt());
            }
        }else{
            $this->_oError->display('Provide all fields', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Show resource
     * @param $mId
     * @return void
     */
    public function show($mId)
    {
        $this->oView->assign('product', $this->_oModelProducts->where(['id'=>$mId])->first());
    }

    /**
     * Enable product
     * @param $mId
     * @return void
     */
    public function enable($mId)
    {
        if ($this->_oModelProducts->where('id','=', $mId)->update(['status'=>'enable'])){
            $this->oView->assign(
                [
                    'message' => 'Product successfully enabled'
                ]
            );
        }else{
            $this->_oError->display('Can not enable the product', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Disable product
     * @param $mId
     * @return void
     */
    public function disable($mId)
    {
        if ($this->_oModelProducts->where('id','=', $mId)->update(['status'=>'disable'])){
            $this->oView->assign(
                [
                    'message' => 'Product successfully disabled'
                ]
            );
        }else{
            $this->_oError->display('Can not disable the product', 406, $this->_oRequest->getExt());
        }
    }
}

?>