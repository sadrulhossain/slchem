<?php

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
//global $prefix;
$prefix = env('PREFIX');
//exit;
Route::group(['middleware' => 'auth'], function () use($prefix) {

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::post('dashboard/getCertificateRelatedProducts', 'Admin\DashboardController@getCertificateRelatedProducts');
    Route::post('dashboard/getBuyerRelatedProducts', 'Admin\DashboardController@getBuyerRelatedProducts');
    Route::post('dashboard/todaysRecipieView', 'Admin\DashboardController@todaysRecipieView');
    Route::post('dashboard/totalActiveRecipieView', 'Admin\DashboardController@totalActiveRecipieView');
    Route::post('dashboard/totalActiveProductsView', 'Admin\DashboardController@totalActiveProductsView');
    Route::post('dashboard/todaysBatchCardView', 'Admin\DashboardController@todaysBatchCardView');
    Route::post('dashboard/todaysDeliverdDemandLetterView', 'Admin\DashboardController@todaysDeliverdDemandLetterView');
    Route::post('dashboard/todaysDeliverdStoreDemandLetterView', 'Admin\DashboardController@todaysDeliverdStoreDemandLetterView');
    Route::post('dashboard/lowQuantityProductsView', 'Admin\DashboardController@lowQuantityProductsView');
    Route::post('dashboard/todaysToatlBatchCardQuantityView', 'Admin\DashboardController@todaysTotalBatchCardQuantityView');
    Route::post('dashboard/todaysReconciliationMismatchView', 'Admin\DashboardController@todaysReconciliationMismatchView');
    Route::post('dashboard/todaysBatchCardWithDemandLetterView', 'Admin\DashboardController@todaysBatchCardWithDemandLetterView');

    //setRecordPerPage
    Route::post('setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('changePassword', 'UserController@changePassword');
    Route::post('changePassword', 'UserController@updatePassword');



    /* Acl Access To Methods */
    Route::get('aclAccessToMethods', 'AclAccessToMethodsController@index');
    Route::get('aclAccessToMethods/addAccessMethod', 'AclAccessToMethodsController@addAccessMethod');
    Route::post('aclAccessToMethods/accessToMethodSave', 'AclAccessToMethodsController@accessToMethodSave');
    Route::post('aclAccessToMethods/getAccessMethod', 'AclAccessToMethodsController@getAccessMethod');



//    //wash
//    Route::post('wash/filter/', 'WashController@filter');
//    Route::get('wash', 'WashController@index')->name('wash.index');
//    Route::get('wash/create', 'WashController@create')->name('wash.create');
//    Route::post('wash', 'WashController@store')->name('wash.store');
//    Route::get('wash/{id}/edit', 'WashController@edit')->name('wash.edit');
//    Route::patch('wash/{id}', 'WashController@update')->name('wash.update');
//    Route::delete('wash/{id}', 'WashController@destroy')->name('wash.destroy');
});


//ACL ACCESS GROUP MIDDLEWARE
Route::group(['middleware' => ['auth', 'aclgroup']], function () use($prefix) {

    //user
    Route::post('user/filter/', 'UserController@filter');
    Route::get('user', 'UserController@index')->name('user.index');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('user/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

    //department
    Route::post('department/filter/', 'DepartmentController@filter');
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::get('department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //designation
    Route::post('designation/filter/', 'DesignationController@filter');
    Route::get('designation', 'DesignationController@index')->name('designation.index');
    Route::get('designation/create', 'DesignationController@create')->name('designation.create');
    Route::post('designation', 'DesignationController@store')->name('designation.store');
    Route::get('designation/{id}/edit', 'DesignationController@edit')->name('designation.edit');
    Route::patch('designation/{id}', 'DesignationController@update')->name('designation.update');
    Route::delete('designation/{id}', 'DesignationController@destroy')->name('designation.destroy');

    //Cofiguration
    Route::get('configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('configuration/{id}/edit', 'ConfigurationController@edit')->name('configuration.edit');
    Route::patch('configuration/{id}', 'ConfigurationController@update')->name('configuration.update');

    //ppe
    Route::post('ppe/filter/', 'PpeController@filter');
    Route::get('ppe', 'PpeController@index')->name('ppe.index');
    Route::get('ppe/create', 'PpeController@create')->name('ppe.create');
    Route::post('ppe', 'PpeController@store')->name('ppe.store');
    Route::get('ppe/{id}/edit', 'PpeController@edit')->name('ppe.edit');
    Route::patch('ppe/{id}', 'PpeController@update')->name('ppe.update');
    Route::delete('ppe/{id}', 'PpeController@destroy')->name('ppe.destroy');

    //product category
    Route::post('productCategory/filter/', 'ProductCategoryController@filter');
    Route::get('productCategory', 'ProductCategoryController@index')->name('productCategory.index');
    Route::get('productCategory/create', 'ProductCategoryController@create')->name('productCategory.create');
    Route::post('productCategory', 'ProductCategoryController@store')->name('productCategory.store');
    Route::get('productCategory/{id}/edit', 'ProductCategoryController@edit')->name('productCategory.edit');
    Route::patch('productCategory/{id}', 'ProductCategoryController@update')->name('productCategory.update');
    Route::delete('productCategory/{id}', 'ProductCategoryController@destroy')->name('productCategory.destroy');

    //measurement unit
    Route::post('measureUnit/filter/', 'MeasureUnitController@filter');
    Route::get('measureUnit', 'MeasureUnitController@index')->name('measureUnit.index');
    Route::get('measureUnit/create', 'MeasureUnitController@create')->name('measureUnit.create');
    Route::post('measureUnit', 'MeasureUnitController@store')->name('measureUnit.store');
    Route::get('measureUnit/{id}/edit', 'MeasureUnitController@edit')->name('measureUnit.edit');
    Route::patch('measureUnit/{id}', 'MeasureUnitController@update')->name('measureUnit.update');
    Route::delete('measureUnit/{id}', 'MeasureUnitController@destroy')->name('measureUnit.destroy');

    //secondary unit
    Route::post('secondaryUnit/filter/', 'SecondaryUnitController@filter');
    Route::get('secondaryUnit', 'SecondaryUnitController@index')->name('secondaryUnit.index');
    Route::get('secondaryUnit/create', 'SecondaryUnitController@create')->name('secondaryUnit.create');
    Route::post('secondaryUnit', 'SecondaryUnitController@store')->name('secondaryUnit.store');
    Route::get('secondaryUnit/{id}/edit', 'SecondaryUnitController@edit')->name('secondaryUnit.edit');
    Route::patch('secondaryUnit/{id}', 'SecondaryUnitController@update')->name('secondaryUnit.update');
    Route::delete('secondaryUnit/{id}', 'SecondaryUnitController@destroy')->name('secondaryUnit.destroy');

    //certificate
    Route::post('certificate/filter/', 'CertificateController@filter');
    Route::get('certificate', 'CertificateController@index')->name('certificate.index');
    Route::get('certificate/create', 'CertificateController@create')->name('certificate.create');
    Route::post('certificate', 'CertificateController@store')->name('certificate.store');
    Route::get('certificate/{id}/edit', 'CertificateController@edit')->name('certificate.edit');
    Route::patch('certificate/{id}', 'CertificateController@update')->name('certificate.update');
    Route::delete('certificate/{id}', 'CertificateController@destroy')->name('certificate.destroy');

    //manufacturer
    Route::post('manufacturer/filter/', 'ManufacturerController@filter');
    Route::get('manufacturer', 'ManufacturerController@index')->name('manufacturer.index');
    Route::get('manufacturer/create', 'ManufacturerController@create')->name('manufacturer.create');
    Route::post('manufacturer', 'ManufacturerController@store')->name('manufacturer.store');
    Route::get('manufacturer/{id}/edit', 'ManufacturerController@edit')->name('manufacturer.edit');
    Route::patch('manufacturer/{id}', 'ManufacturerController@update')->name('manufacturer.update');
    Route::delete('manufacturer/{id}', 'ManufacturerController@destroy')->name('manufacturer.destroy');

    //mfAddressBook
    Route::post('mfAddressBook/filter/', 'MfAddressBookController@filter');
    Route::get('mfAddressBook', 'MfAddressBookController@index')->name('mfAddressBook.index');
    Route::get('mfAddressBook/create', 'MfAddressBookController@create')->name('mfAddressBook.create');
    Route::post('mfAddressBook', 'MfAddressBookController@store')->name('mfAddressBook.store');
    Route::get('mfAddressBook/{id}/edit', 'MfAddressBookController@edit')->name('mfAddressBook.edit');
    Route::patch('mfAddressBook/{id}', 'MfAddressBookController@update')->name('mfAddressBook.update');
    Route::delete('mfAddressBook/{id}', 'MfAddressBookController@destroy')->name('mfAddressBook.destroy');

    //supplier type
    Route::post('supplierType/filter/', 'SupplierTypeController@filter');
    Route::get('supplierType', 'SupplierTypeController@index')->name('supplierType.index');
    Route::get('supplierType/create', 'SupplierTypeController@create')->name('supplierType.create');
    Route::post('supplierType', 'SupplierTypeController@store')->name('supplierType.store');
    Route::get('supplierType/{id}/edit', 'SupplierTypeController@edit')->name('supplierType.edit');
    Route::patch('supplierType/{id}', 'SupplierTypeController@update')->name('supplierType.update');
    Route::delete('supplierType/{id}', 'SupplierTypeController@destroy')->name('supplierType.destroy');

    //supplier
    Route::post('supplier/filter/', 'SupplierController@filter');
    Route::get('supplier', 'SupplierController@index')->name('supplier.index');
    Route::get('supplier/create', 'SupplierController@create')->name('supplier.create');
    Route::post('supplier', 'SupplierController@store')->name('supplier.store');
    Route::get('supplier/{id}/edit', 'SupplierController@edit')->name('supplier.edit');
    Route::patch('supplier/{id}', 'SupplierController@update')->name('supplier.update');
    Route::delete('supplier/{id}', 'SupplierController@destroy')->name('supplier.destroy');

    //product function
    Route::post('productFunction/filter/', 'ProductFunctionController@filter');
    Route::get('productFunction', 'ProductFunctionController@index')->name('productFunction.index');
    Route::get('productFunction/create', 'ProductFunctionController@create')->name('productFunction.create');
    Route::post('productFunction', 'ProductFunctionController@store')->name('productFunction.store');
    Route::get('productFunction/{id}/edit', 'ProductFunctionController@edit')->name('productFunction.edit');
    Route::patch('productFunction/{id}', 'ProductFunctionController@update')->name('productFunction.update');
    Route::delete('productFunction/{id}', 'ProductFunctionController@destroy')->name('productFunction.destroy');

    //substanceCas
    Route::post('substanceCas/filter/', 'SubstanceCasController@filter');
    Route::get('substanceCas', 'SubstanceCasController@index')->name('substanceCas.index');
    Route::get('substanceCas/create', 'SubstanceCasController@create')->name('substanceCas.create');
    Route::post('substanceCas', 'SubstanceCasController@store')->name('substanceCas.store');
    Route::get('substanceCas/{id}/edit', 'SubstanceCasController@edit')->name('substanceCas.edit');
    Route::patch('substanceCas/{id}', 'SubstanceCasController@update')->name('substanceCas.update');
    Route::delete('substanceCas/{id}', 'SubstanceCasController@destroy')->name('substanceCas.destroy');

    //substanceEc
    Route::post('substanceEc/filter/', 'SubstanceEcController@filter');
    Route::get('substanceEc', 'SubstanceEcController@index')->name('substanceEc.index');
    Route::get('substanceEc/create', 'SubstanceEcController@create')->name('substanceEc.create');
    Route::post('substanceEc', 'SubstanceEcController@store')->name('substanceEc.store');
    Route::get('substanceEc/{id}/edit', 'SubstanceEcController@edit')->name('substanceEc.edit');
    Route::patch('substanceEc/{id}', 'SubstanceEcController@update')->name('substanceEc.update');
    Route::delete('substanceEc/{id}', 'SubstanceEcController@destroy')->name('substanceEc.destroy');

    //hazard category
    Route::post('hazardCategory/filter/', 'HazardCategoryController@filter');
    Route::get('hazardCategory', 'HazardCategoryController@index')->name('hazardCategory.index');
    Route::get('hazardCategory/create', 'HazardCategoryController@create')->name('hazardCategory.create');
    Route::post('hazardCategory', 'HazardCategoryController@store')->name('hazardCategory.store');
    Route::get('hazardCategory/{id}/edit', 'HazardCategoryController@edit')->name('hazardCategory.edit');
    Route::patch('hazardCategory/{id}', 'HazardCategoryController@update')->name('hazardCategory.update');
    Route::delete('hazardCategory/{id}', 'HazardCategoryController@destroy')->name('hazardCategory.destroy');

    //pictogram
    Route::post('pictogram/filter/', 'PictogramController@filter');
    Route::get('pictogram', 'PictogramController@index')->name('pictogram.index');
    Route::get('pictogram/create', 'PictogramController@create')->name('pictogram.create');
    Route::post('pictogram', 'PictogramController@store')->name('pictogram.store');
    Route::get('pictogram/{id}/edit', 'PictogramController@edit')->name('pictogram.edit');
    Route::patch('pictogram/{id}', 'PictogramController@update')->name('pictogram.update');
    Route::delete('pictogram/{id}', 'PictogramController@destroy')->name('pictogram.destroy');

    //hazard class
    Route::post('hazardClass/filter/', 'HazardClassController@filter');
    Route::get('hazardClass', 'HazardClassController@index')->name('hazardClass.index');
    Route::get('hazardClass/create', 'HazardClassController@create')->name('hazardClass.create');
    Route::post('hazardClass', 'HazardClassController@store')->name('hazardClass.store');
    Route::get('hazardClass/{id}/edit', 'HazardClassController@edit')->name('hazardClass.edit');
    Route::patch('hazardClass/{id}', 'HazardClassController@update')->name('hazardClass.update');
    Route::delete('hazardClass/{id}', 'HazardClassController@destroy')->name('hazardClass.destroy');

    //product
    //Route::post('product/manageProduct', 'ProductController@manageProduct');
    Route::post('product/filter/', 'ProductController@filter');
    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product/loadProductNameCreate', 'ProductController@loadProductNameCreate');
    Route::post('product', 'ProductController@store')->name('product.store');
    Route::get('product/{id}/edit', 'ProductController@edit')->name('product.edit');
    Route::post('product/loadProductNameEdit', 'ProductController@loadProductNameEdit');
    Route::patch('product/{id}', 'ProductController@update')->name('product.update');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');
    //product manage
    Route::get('product/manageProduct/{id}', 'ProductController@manageProduct');
    Route::post('product/generateSubstanceName/', 'ProductController@generateSubstanceName');
    Route::post('product/generateEcSubstanceName/', 'ProductController@generateEcSubstanceName');
    Route::post('product/saveSubstance', 'ProductController@saveSubstance');
    Route::post('product/newCertificateRow/', 'ProductController@newCertificateRow');
    Route::post('product/saveCertificate', 'ProductController@saveCertificate');
    Route::post('product/newGlRow/', 'ProductController@newGlRow');
    Route::post('product/saveGl', 'ProductController@saveGl');
    Route::post('product/newBplRow/', 'ProductController@newBplRow');
    Route::post('product/newMplRow/', 'ProductController@newMplRow');
    Route::post('product/savePl', 'ProductController@savePl');
    Route::post('product/savePpe', 'ProductController@savePpe');
    Route::post('product/saveHazardCat', 'ProductController@saveHazardCat');
    Route::post('product/saveSupplier', 'ProductController@saveSupplier');
    Route::post('product/saveManufacturer', 'ProductController@saveManufacturer');
    //product approve
    Route::post('product/pendingFilter/', 'ProductController@pendingFilter');
    Route::get('product/approvalProduct', 'ProductController@approvalProduct');
    Route::post('product/doApprove/{id}', 'ProductController@doApprove');
    Route::get('product/lowQuantityProduct', 'ProductController@lowQuantityProduct');

    //product to supplier
    Route::get('productToSupplier', 'ProductToSupplierController@index')->name('productToSupplier.index');
    Route::post('productToSupplier/getProducts/', 'ProductToSupplierController@getProducts');
    Route::post('productToSupplier/saveProducts/', 'ProductToSupplierController@saveProducts');

    //product to manufacturer
    Route::get('productToManufacturer', 'ProductToManufacturerController@index')->name('productToManufacturer.index');
    Route::post('productToManufacturer/getProducts/', 'ProductToManufacturerController@getProducts');
    Route::post('productToManufacturer/saveProducts/', 'ProductToManufacturerController@saveProducts');

    //shade
    Route::post('shade/filter', 'ShadeController@filter');
    Route::get('shade', 'ShadeController@index')->name('shade.index');
    Route::get('shade/create', 'ShadeController@create')->name('shade.create');
    Route::post('shade', 'ShadeController@store')->name('shade.store');
    Route::get('shade/{id}/edit', 'ShadeController@edit')->name('shade.edit');
    Route::patch('shade/{id}', 'ShadeController@update')->name('shade.update');
    Route::delete('shade/{id}', 'ShadeController@destroy')->name('shade.destroy');

    //Process Type 
    Route::post('processType/filter', 'ProcessTypeController@filter');
    Route::get('processType', 'ProcessTypeController@index')->name('processType.index');
    Route::get('processType/create', 'ProcessTypeController@create')->name('processType.create');
    Route::post('processType', 'ProcessTypeController@store')->name('processType.store');
    Route::get('processType/{id}/edit', 'ProcessTypeController@edit')->name('processType.edit');
    Route::patch('processType/{id}', 'ProcessTypeController@update')->name('processType.update');
    Route::delete('processType/{id}', 'ProcessTypeController@destroy')->name('processType.destroy');

    //process
    Route::post('process/filter/', 'ProcessController@filter');
    Route::get('process', 'ProcessController@index')->name('process.index');
    Route::get('process/create', 'ProcessController@create')->name('process.create');
    Route::post('process', 'ProcessController@store')->name('process.store');
    Route::get('process/{id}/edit', 'ProcessController@edit')->name('process.edit');
    Route::patch('process/{id}', 'ProcessController@update')->name('process.update');
    Route::delete('process/{id}', 'ProcessController@destroy')->name('process.destroy');

    //product to process
    Route::get('productToProcess', 'ProductToProcessController@index')->name('productToProcess.index');
    Route::post('productToProcess/getProducts/', 'ProductToProcessController@getProducts');
    Route::post('productToProcess/saveProducts/', 'ProductToProcessController@saveProducts');

    //buyer
    Route::post('buyer/filter/', 'BuyerController@filter');
    Route::get('buyer', 'BuyerController@index')->name('buyer.index');
    Route::get('buyer/create', 'BuyerController@create')->name('buyer.create');
    Route::post('buyer', 'BuyerController@store')->name('buyer.store');
    Route::get('buyer/{id}/edit', 'BuyerController@edit')->name('buyer.edit');
    Route::patch('buyer/{id}', 'BuyerController@update')->name('buyer.update');
    Route::delete('buyer/{id}', 'BuyerController@destroy')->name('buyer.destroy');

    //factory
    Route::post('factory/filter/', 'FactoryController@filter');
    Route::get('factory', 'FactoryController@index')->name('factory.index');
    Route::get('factory/create', 'FactoryController@create')->name('factory.create');
    Route::post('factory', 'FactoryController@store')->name('factory.store');
    Route::get('factory/{id}/edit', 'FactoryController@edit')->name('factory.edit');
    Route::patch('factory/{id}', 'FactoryController@update')->name('factory.update');
    Route::delete('factory/{id}', 'FactoryController@destroy')->name('factory.destroy');

    //garments type
    Route::post('garmentsType/filter/', 'GarmentsTypeController@filter');
    Route::get('garmentsType', 'GarmentsTypeController@index')->name('garmentsType.index');
    Route::get('garmentsType/create', 'GarmentsTypeController@create')->name('garmentsType.create');
    Route::post('garmentsType', 'GarmentsTypeController@store')->name('garmentsType.store');
    Route::get('garmentsType/{id}/edit', 'GarmentsTypeController@edit')->name('garmentsType.edit');
    Route::patch('garmentsType/{id}', 'GarmentsTypeController@update')->name('garmentsType.update');
    Route::delete('garmentsType/{id}', 'GarmentsTypeController@destroy')->name('garmentsType.destroy');

    //wash type
    Route::post('washType/filter/', 'WashTypeController@filter');
    Route::get('washType', 'WashTypeController@index')->name('washType.index');
    Route::get('washType/create', 'WashTypeController@create')->name('washType.create');
    Route::post('washType', 'WashTypeController@store')->name('washType.store');
    Route::get('washType/{id}/edit', 'WashTypeController@edit')->name('washType.edit');
    Route::patch('washType/{id}', 'WashTypeController@update')->name('washType.update');
    Route::delete('washType/{id}', 'WashTypeController@destroy')->name('washType.destroy');

    //style
    Route::post('style/filter/', 'StyleController@filter');
    Route::get('style', 'StyleController@index')->name('style.index');
    Route::get('style/create', 'StyleController@create')->name('style.create');
    Route::post('style', 'StyleController@store')->name('style.store');
    Route::get('style/{id}/edit', 'StyleController@edit')->name('style.edit');
    Route::patch('style/{id}', 'StyleController@update')->name('style.update');
    Route::delete('style/{id}', 'StyleController@destroy')->name('style.destroy');

    //dryer category
    Route::post('dryerCategory/filter/', 'DryerCategoryController@filter');
    Route::get('dryerCategory', 'DryerCategoryController@index')->name('dryerCategory.index');
    Route::get('dryerCategory/create', 'DryerCategoryController@create')->name('dryerCategory.create');
    Route::post('dryerCategory', 'DryerCategoryController@store')->name('dryerCategory.store');
    Route::get('dryerCategory/{id}/edit', 'DryerCategoryController@edit')->name('dryerCategory.edit');
    Route::patch('dryerCategory/{id}', 'DryerCategoryController@update')->name('dryerCategory.update');
    Route::delete('dryerCategory/{id}', 'DryerCategoryController@destroy')->name('dryerCategory.destroy');

    //dryer type
    Route::post('dryerType/filter/', 'DryerTypeController@filter');
    Route::get('dryerType', 'DryerTypeController@index')->name('dryerType.index');
    Route::get('dryerType/create', 'DryerTypeController@create')->name('dryerType.create');
    Route::post('dryerType', 'DryerTypeController@store')->name('dryerType.store');
    Route::get('dryerType/{id}/edit', 'DryerTypeController@edit')->name('dryerType.edit');
    Route::patch('dryerType/{id}', 'DryerTypeController@update')->name('dryerType.update');
    Route::delete('dryerType/{id}', 'DryerTypeController@destroy')->name('dryerType.destroy');

    //dryer machine
    Route::post('dryerMachine/filter/', 'DryerMachineController@filter');
    Route::get('dryerMachine', 'DryerMachineController@index')->name('dryerMachine.index');
    Route::get('dryerMachine/create', 'DryerMachineController@create')->name('dryerMachine.create');
    Route::post('dryerMachine', 'DryerMachineController@store')->name('dryerMachine.store');
    Route::get('dryerMachine/{id}/edit', 'DryerMachineController@edit')->name('dryerMachine.edit');
    Route::patch('dryerMachine/{id}', 'DryerMachineController@update')->name('dryerMachine.update');
    Route::delete('dryerMachine/{id}', 'DryerMachineController@destroy')->name('dryerMachine.destroy');

    //machine model
    Route::post('machineModel/filter/', 'MachineModelController@filter');
    Route::get('machineModel', 'MachineModelController@index')->name('machineModel.index');
    Route::get('machineModel/create', 'MachineModelController@create')->name('machineModel.create');
    Route::post('machineModel', 'MachineModelController@store')->name('machineModel.store');
    Route::get('machineModel/{id}/edit', 'MachineModelController@edit')->name('machineModel.edit');
    Route::patch('machineModel/{id}', 'MachineModelController@update')->name('machineModel.update');
    Route::delete('machineModel/{id}', 'MachineModelController@destroy')->name('machineModel.destroy');

    //machine
    Route::post('machine/filter/', 'MachineController@filter');
    Route::get('machine', 'MachineController@index')->name('machine.index');
    Route::get('machine/create', 'MachineController@create')->name('machine.create');
    Route::post('machine', 'MachineController@store')->name('machine.store');
    Route::get('machine/{id}/edit', 'MachineController@edit')->name('machine.edit');
    Route::patch('machine/{id}', 'MachineController@update')->name('machine.update');
    Route::delete('machine/{id}', 'MachineController@destroy')->name('machine.destroy');

    //hydro machime
    Route::post('hydroMachine/filter/', 'HydroMachineController@filter');
    Route::get('hydroMachine', 'HydroMachineController@index')->name('hydroMachine.index');
    Route::get('hydroMachine/create', 'HydroMachineController@create')->name('hydroMachine.create');
    Route::post('hydroMachine', 'HydroMachineController@store')->name('hydroMachine.store');
    Route::get('hydroMachine/{id}/edit', 'HydroMachineController@edit')->name('hydroMachine.edit');
    Route::patch('hydroMachine/{id}', 'HydroMachineController@update')->name('hydroMachine.update');
    Route::delete('hydroMachine/{id}', 'HydroMachineController@destroy')->name('hydroMachine.destroy');

    //initial balance
    Route::post('initialBalance/set/', 'InitialBalanceController@setBalance');
    Route::get('initialBalance', 'InitialBalanceController@create');
    Route::post('initialBalance/getSupplierManufacturer/', 'InitialBalanceController@getSupplierManufacturer');
    Route::post('initialBalance/getManufacturerAddress/', 'InitialBalanceController@getManufacturerAddress');
    Route::post('initialBalance/purchaseNew/', 'InitialBalanceController@purchaseNew');
    Route::post('initialBalance/productHints', 'InitialBalanceController@productHints');

    //purchase product
    Route::post('productCheckIn/filter/', 'ProductCheckInController@filter');
    Route::get('productCheckIn', 'ProductCheckInController@create');
    Route::post('productCheckIn/purchaseProduct/', 'ProductCheckInController@purchaseProduct');
    Route::post('productCheckIn/getSupplierManufacturer/', 'ProductCheckInController@getSupplierManufacturer');
    Route::post('productCheckIn/getManufacturerAddress/', 'ProductCheckInController@getManufacturerAddress');
    Route::post('productCheckIn/purchaseNew/', 'ProductCheckInController@purchaseNew');
    Route::post('productCheckIn/productHints', 'ProductCheckInController@productHints');

    //product purchase list
    Route::get('productCheckInList', 'ProductCheckInListController@index');
    Route::post('productCheckInList/getProductDetails', 'ProductCheckInListController@getProductDetails');
    Route::post('productCheckInList/filter', 'ProductCheckInListController@filter');

    //Product Consumption
    Route::get('productConsumption', 'ProductConsumptionController@create');
    Route::post('productConsumption/consumeProduct/', 'ProductConsumptionController@consumeProduct');
    Route::post('productConsumption/purchaseNew/', 'ProductConsumptionController@purchaseNew');
    Route::post('productConsumption/productHints', 'ProductConsumptionController@productHints');

    //consumption list
    Route::get('productConsumptionList', 'ProductConsumptionListController@index');
    Route::post('productConsumptionList/filter/', 'ProductConsumptionListController@filter');
    Route::post('productConsumptionList/getProductDetails', 'ProductConsumptionListController@getProductConsumptionDetails');

    //approve consumption
    Route::get('productConsumptionApproval', 'ProductConsumptionApprovalController@index');
    Route::post('productConsumptionApproval/pendingFilter/', 'ProductConsumptionApprovalController@pendingFilter');
    Route::post('productConsumptionApproval/getProductDetails', 'ProductConsumptionApprovalController@getProductConsumptionDetails');
    Route::post('productConsumptionApproval/getLotWiseProductDetails', 'ProductConsumptionApprovalController@getLotWiseProductDetails');
    Route::post('productConsumptionApproval/doApprove', 'ProductConsumptionApprovalController@doApprove');

    //recipe
    Route::post('recipe/filter/', 'RecipeController@filter');
    Route::get('recipe', 'RecipeController@index')->name('recipe.index');
    Route::get('recipe/create', 'RecipeController@create')->name('recipe.create');
    Route::post('recipe/getFactoryCode/', 'RecipeController@getFactoryCode');
    Route::post('recipe/getDryerMachine/', 'RecipeController@getDryerMachine');
    Route::post('recipe/getProducts', 'RecipeController@getProcessWiseProduct');
    Route::post('recipe/addProcess/', 'RecipeController@addProcess');
    Route::post('recipe/updateProcess/', 'RecipeController@updateProcess');
    Route::post('recipe/saveRecipe', 'RecipeController@saveRecipe');
    Route::get('recipe/{id}/edit', 'RecipeController@edit')->name('recipe.edit');
    Route::post('recipe/getFactoryCodeEdited/', 'RecipeController@getFactoryCodeEdited');
    Route::post('recipe/getDryerMachineEdited/', 'RecipeController@getDryerMachineEdited');
    Route::post('recipe/getProductsEdited', 'RecipeController@getProcessWiseProductEdited');
    Route::post('recipe/addProcessEdited/', 'RecipeController@addProcessEdited');
    Route::post('recipe/updateProcessEdited/', 'RecipeController@updateProcessEdited');
    Route::post('recipe/updateRecipe', 'RecipeController@updateRecipe');
    Route::delete('recipe/{id}', 'RecipeController@destroy')->name('recipe.destroy');
    Route::post('recipe/getDetails', 'RecipeController@getRecipeDetails');
    Route::get('recipe/getDetails/{id}/{view?}', 'RecipeController@getRecipeDetails');
    Route::post('recipe/showDeactivateDiv', 'RecipeController@showDeactivate');
    Route::post('recipe/showActivateDiv', 'RecipeController@showActivate');
    Route::post('recipe/showHistoryDiv', 'RecipeController@showHistory');
    Route::get('recipe/active/{id}/{type?}', 'RecipeController@doActive');
    Route::get('recipe/deactive/{id}/{type?}', 'RecipeController@makeDeactive');
    Route::post('recipe/activate', 'RecipeController@activate');
    Route::post('recipe/deactivate', 'RecipeController@deactivate');
    Route::post('recipe/finalize', 'RecipeController@recipeFinalize');


    //finalized recipe
    Route::get('finalizedRecipe', 'FinalizedRecipeController@index');
    Route::post('finalizedRecipe/getDetails', 'FinalizedRecipeController@getRecipeDetails');
    Route::get('finalizedRecipe/getDetails/{id}/{view?}', 'FinalizedRecipeController@getRecipeDetails');
    Route::post('finalizedRecipe/filter/', 'FinalizedRecipeController@filter');
    Route::post('finalizedRecipe/showHistoryDiv', 'FinalizedRecipeController@showHistory');
    Route::post('finalizedRecipe/showDeactivateDiv', 'FinalizedRecipeController@showDeactivate');
    Route::post('finalizedRecipe/showActivateDiv', 'FinalizedRecipeController@showActivate');
    Route::post('finalizedRecipe/deactivate', 'FinalizedRecipeController@deactivate');


    //batch card
    Route::get('batchCard', 'BatchCardController@index')->name('batchCard.index');
    Route::post('batchCard/filter', 'BatchCardController@filter');
    Route::get('batchCard/create', 'BatchCardController@create')->name('batchCard.create');
    Route::post('batchCard/saveBatchCard', 'BatchCardController@saveBatchCard');
    Route::get('batchCard/{id}/edit', 'BatchCardController@edit')->name('batchCard.edit');
    Route::post('batchCard/getDetails', 'BatchCardController@details');
    Route::get('batchCard/getDetails/{id}/{view?}', 'BatchCardController@details');
    Route::post('batchCard/getRecipeDetails', 'BatchCardController@recipeDetails');
    Route::get('batchCard/getRecipeDetails/{id}/{view?}', 'BatchCardController@recipeDetails');
    Route::post('batchCard/updateInformation', 'BatchCardController@updateInformation');
    Route::post('batchCard/getRecipeInfo', 'BatchCardController@getRecipeInfo');
    Route::post('batchCard/manageInfo', 'BatchCardController@manageInfo');
    Route::post('batchCard/loadBatchToken', 'BatchCardController@loadBatchToken');

    //generate demand
    Route::get('generateDemand', 'GenerateDemandController@generateDemand');
    Route::post('generateDemand/getRecipeInfo', 'GenerateDemandController@getRecipeInfo');
    Route::post('generateDemand/saveDemand', 'GenerateDemandController@saveDemand');
    Route::post('generateDemand/loadBatchToken', 'GenerateDemandController@loadBatchTokenToGenerateDemand');

    //demand
    Route::post('demand/filter', 'DemandController@filter');
    Route::get('demand', 'DemandController@index')->name('demand.index');
    Route::post('demand/loadToken', 'DemandController@loadTokenNo');
    Route::post('demand/loadBatchToken', 'DemandController@loadBatchToken');
    Route::post('demand/getDemandId', 'DemandController@setDemandId');
    Route::post('demand/getDetails', 'DemandController@details');
    Route::get('demand/getDetails/{id}/{view?}', 'DemandController@details');
    Route::post('demand/printDemandList/{view?}', 'DemandController@printDemandList');

    //deliver chemicals
    Route::get('deliverChemicals', 'DeliverChemicalsController@index');
    Route::post('deliverChemicals/filter', 'DeliverChemicalsController@filter');
    Route::post('deliverChemicals/loadTokenToDeliver', 'DeliverChemicalsController@loadTokenToDeliver');
    Route::post('deliverChemicals/loadBatchTokenForDeliver', 'DeliverChemicalsController@loadBatchTokenForDeliver');
    Route::post('deliverChemicals/getDemandId', 'DeliverChemicalsController@setDemandId');
    Route::post('deliverChemicals/getDetails', 'DeliverChemicalsController@details');
    Route::get('deliverChemicals/getDetails/{id}/{view?}', 'DeliverChemicalsController@details');
    Route::post('deliverChemicals/deliver', 'DeliverChemicalsController@doDeliver');

    //delivered chemicals list
    Route::get('deliveredChemicalsList', 'DeliveredChemicalsListController@index');
    Route::post('deliveredChemicalsList/filter', 'DeliveredChemicalsListController@filter');
    Route::post('deliveredChemicalsList/loadBatchTokenForDeliveredDemand', 'DeliveredChemicalsListController@loadBatchTokenForDeliveredDemand');
    Route::post('deliveredChemicalsList/loadTokenforDelivered', 'DeliveredChemicalsListController@loadTokenforDelivered');
    Route::post('deliveredChemicalsList/makeDemandId', 'DeliveredChemicalsListController@makeMultiDemandId');
    Route::post('deliveredChemicalsList/viewMultipleDetails', 'DeliveredChemicalsListController@multipleDemandDetails');
    Route::get('deliveredChemicalsList/viewMultipleDetails/{id}/{view?}', 'DeliveredChemicalsListController@multipleDemandDetails');
    Route::post('deliveredChemicalsList/getDetails', 'DeliveredChemicalsListController@details');
    Route::get('deliveredChemicalsList/getDetails/{id}/{view?}', 'DeliveredChemicalsListController@details');

    //substore demand
    Route::get('substoreDemand', 'SubstoreDemandController@create')->name('substoreDemand.create');
    Route::post('substoreDemand/purchaseNew', 'SubstoreDemandController@purchaseNew');
    Route::post('substoreDemand/productHints', 'SubstoreDemandController@productHints');
    Route::post('substoreDemand/generateDemand', 'SubstoreDemandController@generateDemand');

    //demand To Deliver
    Route::get('demandToDeliver', 'DemandToDeliverController@index');
    Route::post('demandToDeliver/filter/', 'DemandToDeliverController@filter');
    Route::post('demandToDeliver/getProductDetails', 'DemandToDeliverController@getProductDetails');
    Route::get('demandToDeliver/getProductDetails/{id}/{view?}', 'DemandToDeliverController@getProductDetails');
    Route::post('demandToDeliver/consume', 'DemandToDeliverController@makeConsume');

    //delivered Demand List
    Route::get('deliveredDemandList', 'DeliveredDemandListController@index');
    Route::post('deliveredDemandList/filter', 'DeliveredDemandListController@filter');
    Route::post('deliveredDemandList/getProductDetails', 'DeliveredDemandListController@getProductDetails');
    Route::get('deliveredDemandList/getProductDetails/{id}/{view?}', 'DeliveredDemandListController@getProductDetails');

    //batchcard report
    Route::get('batchCardReport', 'BatchCardReportController@index');
    Route::get('batchCardReport/{view?}', 'BatchCardReportController@index');
    Route::post('batchCardReport/filter', 'BatchCardReportController@filter');
    Route::post('batchCardReport/loadBatchToken', 'BatchCardReportController@loadBatchToken');

    //Check In Report
    Route::get('checkInReport', 'CheckInReportController@index');
    Route::get('checkInReport/{view?}', 'CheckInReportController@index');
    Route::post('checkInReport/getSupplierManufacturer', 'CheckInReportController@getSupplierManufacturer');
    Route::post('checkInReport/filter', 'CheckInReportController@Filter');

    //daily check in  report
    Route::get('dailyCheckInReport', 'DailyCheckInReportController@index');
    Route::get('dailyCheckInReport/{view?}', 'DailyCheckInReportController@index');
    Route::post('dailyCheckInReport/filter', 'DailyCheckInReportController@filter');

    //monthly check in report
    Route::get('monthlyCheckInReport', 'MonthlyCheckInReportController@index');
    Route::get('monthlyCheckInReport/{view?}', 'MonthlyCheckInReportController@index');
    Route::post('monthlyCheckInReport/filter', 'MonthlyCheckInReportController@filter');

    //daily consumption report
    Route::get('dailyConsumptionReport', 'DailyConsumptionReportController@index');
    Route::get('dailyConsumptionReport/{view?}', 'DailyConsumptionReportController@index');
    Route::post('dailyConsumptionReport/filter', 'DailyConsumptionReportController@filter');

    //monthly consumption report
    Route::get('monthlyConsumptionReport', 'MonthlyConsumptionReportController@index');
    Route::get('monthlyConsumptionReport/{view?}', 'MonthlyConsumptionReportController@index');
    Route::post('monthlyConsumptionReport/filter', 'MonthlyConsumptionReportController@filter');

    //compliance report
    Route::get('complianceReport', 'ComplianceReportController@index');
    Route::post('complianceReport/getProduct', 'ComplianceReportController@getProduct');
    Route::post('complianceReport/filter', 'ComplianceReportController@filter');

    //Day Wise Balance Closing
    Route::get('dailyProductStatusReport/dailyProduct', 'DailyProductStatusReportController@generateDateWiseProduct');
    Route::get('dailyProductStatusReport', 'DailyProductStatusReportController@index');
    Route::get('dailyProductStatusReport/{view?}', 'DailyProductStatusReportController@index');
    Route::post('dailyProductStatusReport/filter', 'DailyProductStatusReportController@filter');

    //Month Wise Balance
    Route::get('monthlyProductStatusReport', 'MonthlyProductStatusReportController@index');
    Route::get('monthlyProductStatusReport/{view?}', 'MonthlyProductStatusReportController@index');
    Route::post('monthlyProductStatusReport/filter', 'MonthlyProductStatusReportController@filter');

    //Ledger Report
    Route::get('ledgerReport', 'LedgerReportController@index');
    Route::get('ledgerReport/{view?}', 'LedgerReportController@index');
    Route::post('ledgerReport/filter', 'LedgerReportController@filter');

    //daily substore report
    Route::get('dailySubstoreReport', 'DailySubstoreReportController@index');
    Route::get('dailySubstoreReport/{view?}', 'DailySubstoreReportController@index');
    Route::post('dailySubstoreReport/filter', 'DailySubstoreReportController@filter');

    //monthly substore report
    Route::get('monthlySubstoreReport', 'MonthlySubstoreReportController@index');
    Route::get('monthlySubstoreReport/{view?}', 'MonthlySubstoreReportController@index');
    Route::post('monthlySubstoreReport/filter', 'MonthlySubstoreReportController@filter');

    //season
    Route::post('season/filter/', 'SeasonController@filter');
    Route::get('season', 'SeasonController@index')->name('season.index');
    Route::get('season/create', 'SeasonController@create')->name('season.create');
    Route::post('season', 'SeasonController@store')->name('season.store');
    Route::get('season/{id}/edit', 'SeasonController@edit')->name('season.edit');
    Route::patch('season/{id}', 'SeasonController@update')->name('season.update');
    Route::delete('season/{id}', 'SeasonController@destroy')->name('season.destroy');

    //color
    Route::post('color/filter/', 'ColorController@filter');
    Route::get('color', 'ColorController@index')->name('color.index');
    Route::get('color/create', 'ColorController@create')->name('color.create');
    Route::post('color', 'ColorController@store')->name('color.store');
    Route::get('color/{id}/edit', 'ColorController@edit')->name('color.edit');
    Route::patch('color/{id}', 'ColorController@update')->name('color.update');
    Route::delete('color/{id}', 'ColorController@destroy')->name('color.destroy');

    //user group
    Route::post('userGroup/filter/', 'UserGroupController@filter');
    Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');
    Route::get('userGroup/create', 'UserGroupController@create')->name('userGroup.create');
    Route::post('userGroup', 'UserGroupController@store')->name('userGroup.store');
    Route::get('userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
    Route::patch('userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');
    Route::delete('userGroup/{id}', 'UserGroupController@destroy')->name('userGroup.destroy');

    //acl User Group To Access
    Route::get('aclUserGroupToAccess/moduleAccessControl', 'AclUserGroupToAccessController@moduleAccessControl');
    Route::post('aclUserGroupToAccess/relateUserGroupToAccess/', 'AclUserGroupToAccessController@relateUserGroupToAccess');
    Route::post('aclUserGroupToAccess/getAccessControl/', 'AclUserGroupToAccessController@getAccess');
    Route::get('aclUserGroupToAccess/userGroupToAccess', 'AclUserGroupToAccessController@userGroupToAccess');
    Route::post('aclUserGroupToAccess/getUserGroupListToRevoke', 'AclUserGroupToAccessController@getUserGroupListToRevoke');
    Route::post('aclUserGroupToAccess/revokeUserGroupAccess', 'AclUserGroupToAccessController@revokeUserGroupAccess');

    //Reconciliation Report
    Route::get('reconciliationReport', 'ReconciliationReportController@index');
    Route::get('reconciliationReport/{view?}', 'ReconciliationReportController@index');
    Route::post('reconciliationReport/filter', 'ReconciliationReportController@Filter');

    //Detailed Ledger Report
    Route::get('detailedLedgerReport', 'DetailedLedgerReportController@index');
    Route::get('detailedLedgerReport/{view?}', 'DetailedLedgerReportController@index');
    Route::post('detailedLedgerReport/filter', 'DetailedLedgerReportController@Filter');

    //DB Backup
    Route::post('dbBackup/filter', 'DbBackupController@filter');
    Route::post('dbBackup/download', 'DbBackupController@download');
    Route::get('dbBackup', 'DbBackupController@index');

    //Detailed Ledger Report
    Route::get('dbBackupDownloadLogReport', 'DbBackupDownloadLogReportController@index');
    Route::get('dbBackupDownloadLogReport/{view?}', 'DbBackupDownloadLogReportController@index');
    Route::post('dbBackupDownloadLogReport/filter', 'DbBackupDownloadLogReportController@Filter');
});

