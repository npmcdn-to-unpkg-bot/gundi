<?php

namespace Module\Catalog\Component\Controller;

use Core\Contract\Request\IRequest;
use Core\Library\Component\Controller;
use Core\Library\Error\Error;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Module\Catalog\Model\Categories as ModelCategories;
use Core\Contract\Resource\IAddable;
use Core\Contract\Resource\IEditable;
use Core\Contract\Resource\IDeleteable;
use Core\Contract\Resource\IShowable;
use \Watson\Validating\ValidationException;

class Categories extends Controller implements IAddable, IEditable, IShowable, IDeleteable
{
    private $_oModelCategories;
    private $_oRequest;
    private $_oError;

    public function __construct(
        ModelCategories $oModelCategories,
        IRequest $oRequest,
        Error $oError
    )
    {
        $this->_oModelCategories = $oModelCategories;
        $this->_oRequest = $oRequest;
        $this->_oError = $oError;
    }

    /**
     * Get list of categories
     * @return void
     */
    public function index()
    {
        $iPage = (int)$this->_oRequest->get('page') ?: 1;
        $iPerPage = (int)$this->_oRequest->get('per_page') ?: 30;
        $this->oView->assign('categories', $this->_oModelCategories
            ->with('parent')
            ->orderBy($this->_oModelCategories->getKeyName(), 'asc')
            ->paginate($iPerPage, ['*'], 'categories', $iPage));
    }

    /**
     * Show add form
     * @return void
     */
    public function add()
    {
        try {
            if (!empty($this->_oRequest->get('id'))) {
                $this->oView->assign('category', $this->_oModelCategories->findOrFail($this->_oRequest->get('id')));
            }
            $this->oView->assign('categories', $this->_oModelCategories->all());
        } catch (\Exception $oException) {
            $this->_oError->display($oException->getMessage(), 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Save to DB
     * @return void
     */
    public function create()
    {
        try {
            $aCategory = $this->_oRequest->get('category');
            $this->_oModelCategories->fill($aCategory);
            $this->_oModelCategories->saveOrFail();
            $this->oView->assign('response', ['message' => 'Category successfully added', 'category' => $this->_oModelCategories]);
        } catch (ValidationException $e) {
            $this->_oError->display('Provide all fields', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Delete item from database
     * @param string|int $mID
     * @return void
     */
    public function delete($mID)
    {
        if (!$this->_oModelCategories->where($this->_oModelCategories->getKeyName(), $mID)->delete()) {
            $this->_oError->display('Unable delete category from database', 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Show edit form
     * @param string|int $mId
     */
    public function edit($mId)
    {
        try {
            $this->oView->assign('category', $this->_oModelCategories->findOrFail($mId));
        } catch (\Exception $oException) {
            $this->_oError->display('Category not found with id:' . $mId, 404, $this->_oRequest->getExt());
        }
    }

    /**
     * Update category
     * @param string|int $mId
     */
    public function update($mId)
    {

        $aCategory = $this->_oRequest->get('category');
        try {
            $aUpdated['name'] = $aCategory['name'];
            $aUpdated['category_parent_id'] = isset($aCategory['category_parent_id']) ? $aCategory['category_parent_id'] : null;
            $oCategory = $this->_oModelCategories->find($mId);
            $oCategory->fill($aUpdated);
            $oCategory->saveOrFail();
        } catch (ValidationException $e) {
            $this->_oError->display('Provide all fields', 406, $this->_oRequest->getExt());
        } catch (\Exception $e) {
            $this->_oError->display($e->getMessage(), 406, $this->_oRequest->getExt());
        }
    }

    /**
     * Show resource
     * @param $mId
     * @return void
     */
    public function show($mId)
    {
        $this->oView->assign('category', $this->_oModelCategories->where('id', '=', $mId)->first());
    }
}

?>