<?php


namespace SmallRuralDog\Admin\Controllers;


use SmallRuralDog\Admin\Components\Attrs\TransferData;
use SmallRuralDog\Admin\Components\Form\Transfer;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class RoleController extends AdminController
{


    protected function grid()
    {
        $roleModel = config('admin.database.roles_model');

        $grid = new Grid(new $roleModel());

        $grid->quickSearch(['slug', 'name'])
            ->quickSearchPlaceholder('名称 / 标识')
            ->defaultSort('id', 'desc');

        $grid->column('id', 'ID')->width('80px')->sortable(true);
        $grid->column('slug', "标识");
        $grid->column('name', "名称");
        $grid->column('permissions.name', "权限")->component(Tag::make()->type('info'));
        $grid->column('created_at', '添加时间');
        $grid->column('updated_at', '更新时间');
        //$grid->dialogForm($this->form()->isDialog()->labelPosition("top")->className('p-15'), '600px', ['添加角色', '编辑角色']);

        return $grid;
    }

    public function form()
    {
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');
        $form = new Form(new $roleModel());

        $form->labelWidth('120px');

        $form->item('slug', "标识")->required()->inputWidth(6);
        $form->item('name', "名称")->required()->inputWidth(6);
        $form->item('permissions', "权限", 'permissions.id')->component(
            Transfer::make()->data($permissionModel::get()->map(function ($item) {
                return TransferData::make($item->id, $item->name);
            }))->titles(['可授权', '已授权'])->filterable()
        );
        return $form;
    }
}
