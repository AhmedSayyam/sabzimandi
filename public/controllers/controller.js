const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});
function notify(msg, sign){
    Toast.fire({
        icon: sign,
        title: msg
    });
}


app.controller('main_cont', function($scope, $rootScope){
    console.log("Main Controller");
    $rootScope.product_array = [];
    $rootScope.total = 0;
});


app.controller('dashboard_ctrl', function($scope, $translate, $rootScope,$http){
    // console.log("Dashboard Controller");
    $scope.changeLanguage = function (key) {
        console.log("KEY: ",key);
        $rootScope.lang = key;
        $translate.use(key);
    };
    $scope.dashbord_data=function(){
        var postData = $.param({
            from: '',
            to  :''
           
        });


        $http.post('http://localhost/sabzimandi/Admin/dashbord', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    // console.log(response.data.data);
                    $scope.due_total_amount = response.data.data[0];
                    $scope.new_amount   =  response.data.data[1];
                    $scope.dueTotalAmount =  $scope.due_total_amount[0].total_amount;
                  $scope.newAmount      =  $scope.new_amount[0].new_amount;
                  if($scope.dueTotalAmount==null ){
                      $scope.dueTotalAmount=0;
                  }
                  if($scope.newAmount==null){
                      $scope.newAmount=0;
                  }

                    // notify(response.data.error, "success");
                    // setTimeout(function(){
                    //     $("#createDashbordSection").slideUp();
                    //     $scope.dashbord();
                    // }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }
    $scope.dashbord_data();


    $("#pimage").change(function(event){
        console.log(event.files[0]);
    });
});


app.controller('products_ctrl', function($scope, $http, $route){
    // console.log("Staff Controller");
    $("#createProductSection").hide();
    
    $scope.products = function(){
        $http.get('http://localhost/sabzimandi/Admin/products', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.items = response.data.data;
            }
        });
    }
    $scope.products();

   

    $scope.showCreateProductSection = function(){
        $("#createProductSection").slideDown();
    }

    // Dropzone of add staff code starts 
    $scope.$on("$viewContentLoaded", function () {
        $("#staff_dropzone").dropzone({
            url: 'http://localhost/sabzimandi/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.staff_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#staff_dropzone");
        };
        // Dropzone of add product page code ends 
    });

    $scope.disableProduct = function(product_id,status){
        var postData = $.param({
            product_id: product_id,
            status: status,
           
        });
        $http.post('http://localhost/sabzimandi/Admin/product_disable', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    // setTimeout(function(){
                    //     $("#createProductSection").slideUp();
                        $scope.products();
                    // }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.createProduct = function(){
        var postData = $.param({
            label: $scope.ng_pname,
           
        });

        $http.post('http://localhost/sabzimandi/Admin/products', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createProductSection").slideUp();
                        $scope.products();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.delStaff = function(id){
        $http.delete('http://localhost/sabzimandi/Admin/delStaff/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddProduct = function(){
        $("#createProductSection").slideUp();
        
        // notify('Poor Connection!', 'warning');
        // notify('Poor Connection!', 'error');
        // notify('Poor Connection!', 'info');
    }

    // Dropzone of update staff code starts
    $scope.edit_staff_image = ''; 
    $scope.$on("$viewContentLoaded", function () {
        $("#editstaff_dropzone").dropzone({
            url: 'http://localhost/sabzimandi/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.edit_staff_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#staff_dropzone");
        };
        // Dropzone of add product page code ends 
    });


    $scope.updateStaffModal = function(staff){
        $scope.ng_editpid = staff.id;
        $scope.ng_editpname = staff.label;
        $('#modalStaffEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalStaffEdit').modal('show');
    }

    $scope.updateStaff = function(){
        var postData = $.param({
            id: $scope.ng_editpid,
            label: $scope.ng_editpname,
        });

        $http.post('http://localhost/sabzimandi/Admin/products', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalStaffEdit').modal('hide');
                        $scope.products();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });

    }

    $scope.viewStaffModal = function(item){
        $scope.view_pname = item.label;
        // $scope.view_gender = item.gender;
        // $scope.view_sphone = item.phone;
        // $scope.view_sbgroup = item.blood_group;
        // $scope.view_sage = parseFloat(item.age, 10);
        // $scope.view_scnic = item.cnic;
        // $scope.view_saddress = item.address;
        // $scope.view_simage = item.image;
        $('#modalProductsView').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalProductsView').modal('show');
    }

});

app.controller('pos_ctrl', function($scope, $http, $rootScope){
    $scope.getMembers = function(){
        $http.get('http://localhost/sabzimandi/Admin/customer', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.members_data = response.data.data;
                // console.log($scope.members_data);
            }
        });
    }
    $scope.getMembers();

    $scope.activeproducts = function(){
        $http.get('http://localhost/sabzimandi/Admin/activeproducts', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.activeitems = response.data.data;
            }
        });
    }
    $scope.activeproducts();

    // Dropzone of add member page code starts 
    $scope.$on("$viewContentLoaded", function () {
        $("#customer_dropzone").dropzone({
            url: 'http://localhost/sabzimandi/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.member_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#member_dropzone");
        };
        // Dropzone of add product page code ends 
   });

    $scope.add_cart = function(id, label, qty, price){
        $rootScope.product_array.push({
            product_id: id,
            label: label,
            quantity: qty,
            price: price
        });
        $rootScope.total = $rootScope.total + parseFloat(price);
        console.log("Product Array = ", $rootScope.product_array);
        console.log("Total = ", $rootScope.total);
    }

    $scope.createSale = function(cost){
        if(cost==null){
            console.log('customer id null');
                var postData = $.param({
                name: $scope.ng_cname,
                phone: $scope.ng_cphone,
                adress: $scope.ng_caddress,
                image: $scope.member_image,
                products: $rootScope.product_array,
                total_amount:$rootScope.total,
            });

            $http.post('http://localhost/sabzimandi/Admin/sale_item', postData, {
                headers:{
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            }).then(function(response){
                console.log(response);
                if(response.data.status == true){
                    notify(response.data.error, "success");
                }
                else{
                    notify(response.data.error, "error");
                }
            });
        }
        else{
            console.log('id=',cost);

              var postData = $.param({
              c_id:cost,
              products: $rootScope.product_array,
              total_amount:$rootScope.total,
           
               });

        $http.post('http://localhost/sabzimandi/Admin/sale_item', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            console.log(response);
            if(response.data.status == true){
                notify(response.data.error, "success");
            }
            else{
                notify(response.data.error, "error");
            }
        });
        }
      
    }
});

app.controller('package_ctrl', function($scope, $http, $route){
    // console.log("package Controller");
    $("#createCategorySection").hide();
    
    $scope.getPackage = function(){
        $http.get('http://localhost/sabzimandi/Admin/today_list', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.list = response.data.data;
            }
        });
    }
    $scope.getPackage();

    $scope.showCreateCategorySection = function(){
        $("#createCategorySection").slideDown();
    }

    $scope.createPackage = function(){
        var postData = $.param({
            label: $scope.ng_packname,
            amount: $scope.ng_packamount,
            period: $scope.ng_packperiod
        });

        $http.post('http://localhost/sabzimandi/Admin/createPackage', postData, {
            headers:{ 
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createCategorySection").slideUp();
                        $scope.getPackage();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.delPackage = function(id){
        $http.delete('http://localhost/sabzimandi/Admin/delPackage/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddCategory = function(){
        $("#createCategorySection").slideUp();
    }

    

    $scope.updatePackageModal = function(pack){
        $scope.ng_editpackid = pack.package_id;
        $scope.ng_editpackname = pack.package_name;
        $scope.ng_editpackamount = pack.package_amount;
        $scope.ng_editpackperiod = pack.package_period;
        $('#modalPackageEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalPackageEdit').modal('show');
    }

    $scope.updatePackage = function(){
        var postData = $.param({
            id: $scope.ng_editpackid,
            label: $scope.ng_editpackname,
            amount: $scope.ng_editpackamount,
            period: $scope.ng_editpackperiod
        });

        $http.post('http://localhost/sabzimandi/Admin/updatePackage', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalPackageEdit').modal('hide');
                        $scope.getPackage();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

});


app.controller('customers_ctrl', function($scope, $http, $route){
    $(".dimmer").show();
    $("#memberListingSection").hide();

    // console.log("Members Controller");
    $("#createMemberSection").hide();

    $scope.myprint=function(){
        window.print();
    }

    $scope.getMembers = function(){
        $http.get('http://localhost/sabzimandi/Admin/customer', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.members_data = response.data.data;
                $(".dimmer").hide();
                $("#memberListingSection").show();
                // console.log($scope.members_data);
            }
        });
    }
    $scope.getMembers();

    $scope.getcustomerdetail = function(id,from_date){
        var postData = $.param({
            id :id,
            from: from_date,
        });
        $http.post('http://localhost/sabzimandi/Admin/customer_reports',postData, {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.customer_details = response.data.data;
                console.log($scope.customer_details);
            }
        });
    }

    // $scope.products = function(){
    //     $http.get('http://localhost/sabzimandi/Admin/products', $.param({}), {
    //         headers: {
    //             "Content-Type": "application/x-www-form-urlencoded",
    //         },
    //     }).then(function (response) {
    //         if (response.data.status) {
    //             $scope.items = response.data.data;
    //         }
    //     });
    // }
    // $scope.products();

    // $scope.getPackage = function(){
    //     $http.get('http://localhost/sabzimandi/Admin/today_list', $.param({}), {
    //         headers: {
    //             "Content-Type": "application/x-www-form-urlencoded",
    //         },
    //     }).then(function (response) {
    //         if (response.data.status) {
    //             $scope.list = response.data.data;
    //         }
    //     });
    // }
    // $scope.getPackage();


    $scope.showCreateMemberSection = function(){
        $("#createMemberSection").slideDown();
    }


    $scope.createMember = function(){
        var postData = $.param({
            name: $scope.ng_mname,
            phone: $scope.ng_mphone,
            adress: $scope.ng_maddress,
            amount: $scope.ng_mamount,
            cnic: $scope.ng_mcnic,
            image: $scope.member_image
        });

        $http.post('http://localhost/sabzimandi/Admin/customer', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createMemberSection").slideUp();
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.saleDetail = function(id,total){
        $(".dimmer").show();
        $scope.saletotal=total;
        $('#modalMemberView').modal('hide');
        $http.get('http://localhost/sabzimandi/Admin/saleDetail/'+id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.sale_detail = response.data.data;
                $('#modalSaleView').modal('show');
                $(".dimmer").hide();
                console.log($scope.sale_detail);
            }
        });

            $('#modalSaleView').modal({
                backdrop: 'static',
                keyboard: false
            });
            
        
         
    }

    $scope.delMember = function(id){
        $http.delete('http://localhost/sabzimandi/Admin/delMember/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddMember = function(){
        $("#createMemberSection").slideUp();
    }

    // Dropzone of add member page code starts 
    $scope.$on("$viewContentLoaded", function () {
         $("#member_dropzone").dropzone({
             url: 'http://localhost/sabzimandi/Admin/image_upload',
             addRemoveLinks: true,
             maxFiles: 1,
             maxFilesize: 2, // MB
             paramName: "image",
             dictDefaultMessage: "Upload Image",
             acceptedFiles: "image/*",
             success: function (file, response) { 
                 $scope.member_image = response.data;
             }
         });
         $scope.clear = () => {
             var myDropzone = Dropzone.forElement("#member_dropzone");
         };
         // Dropzone of add product page code ends 
    });

    $scope.viewMemberModal = function(member){
        $scope.view_mname = member.name;
        $scope.view_mphone = member.phone;
        $scope.view_maddress = member.adress;
        $scope.view_mimage = member.image;
        $scope.view_mamount = member.amount;
        var from_date=moment(member.created_on).format("YYYY-MM-DD");
        console.log(from_date);
        $('#modalMemberView').modal({
            backdrop: 'static',
            keyboard: false
        });
        $scope.getcustomerdetail(member.id,from_date);
        $('#modalMemberView').modal('show');
    }

    $scope.updateMemberModal = function(member){
        $scope.ng_editmid = member.id;
        $scope.ng_editmname = member.name;
        $scope.ng_editmphone = member.phone;
        $scope.ng_editmcnic = member.cnic;
        $scope.ng_editmaddress = member.adress;
        $scope.ng_editmamount = member.amount;
        $('#modalMemberEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalMemberEdit').modal('show');
    }

    // Dropzone of update member page code starts 
    $scope.edit_member_image = '';
    $scope.$on("$viewContentLoaded", function () {
        $("#editmember_dropzone").dropzone({
            url: 'http://localhost/sabzimandi/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.edit_member_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#member_dropzone");
        };
   });

    $scope.updateMember = function(){
        var postData = $.param({
            id :$scope.ng_editmid,
            name: $scope.ng_editmname,
            phone: $scope.ng_editmphone,
            adress: $scope.ng_editmaddress,
            amount: $scope.ng_editmamount,
            cnic: $scope.ng_editmcnic,
            image: $scope.edit_member_image
        });

        $http.post('http://localhost/sabzimandi/Admin/customer', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalMemberEdit').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                    $('#modalMemberEdit').modal('hide');
                }
            }
            else{
                notify(response.data.error, "error");
                $('#modalMemberEdit').modal('hide');
            }
        });

    }

    $scope.payAmountModel = function(member){
        $scope.ng_cid = member.id;
        $scope.ng_ctotalmamount=member.amount;
        $('#modalpayAmount').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalpayAmount').modal('show');
    }
    $scope.payAmount = function(){
        var postData = $.param({
            c_id :$scope.ng_cid,
            pay_amount: $scope.ng_cmamount,
        });

        $http.post('http://localhost/sabzimandi/Admin/pay_amount', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalpayAmount').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                    $('#modalpayAmount').modal('hide');
                }
            }
            else{
                notify(response.data.error, "error");
                $('#modalpayAmount').modal('hide');
            }
        });

    }

    $scope.AddcustomerModal = function(member){
        $scope.ng_cidd = member.id;
        $('#modaladdcustomer').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modaladdcustomer').modal('show');
    }
    $scope.Addcustomerdata = function(){
        var postData = $.param({
            id :$scope.ng_cidd,
            amount: $scope.ng_cmamount,
        });

        $http.post('http://localhost/sabzimandi/Admin/addcustomer', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modaladdcustomer').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                    $('#modaladdcustomer').modal('hide');
                }
            }
            else{
                notify(response.data.error, "error");
                $('#modaladdcustomer').modal('hide');
            }
        });

    }
});


app.controller('reports_ctrl', function($scope, $http, $route){
    console.log("reports Collection Controller");

    $scope.getAccountList = function(){
        $http.get('http://localhost/sabzimandi/Admin/account_list', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.Account_data = response.data.data;
                console.log($scope.Account_data);
            }
        });
    }
    $scope.getAccountList();

    $scope.collectFeeModal = function(member){
        $('#modalCollectFee').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalCollectFee').modal('show');
        $scope.ng_memid = member.member_id;
        $scope.ng_memname = member.fullname;
        $scope.ng_mempack = member.package_name;
        $scope.ng_memfee = member.package_amount;
        $scope.ng_feedate = new Date();
    }

    $scope.collectFee = function(){
        var postData = $.param({
            mem_id: $scope.ng_memid,
            pack_name: $scope.ng_mempack,
            fee: $scope.ng_memfee,
            deposit_date: moment($scope.ng_feedate).format("YYYY-MM-DD"),
            status: 1
        });

        $http.post('http://localhost/sabzimandi/Admin/collectFee', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalCollectFee').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });


        // notify('Fee Collected Successfully', 'success');
        // $('#modalCollectFee').modal('hide');
    }
});