<template>
    <div v-if="loaded">
        <div class="box-header">
            <h3 class="box-title">All Posts</h3>
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a data-toggle="modal" data-target="#create-item" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;New Post
                </a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="box-body"> 
            <div class="top-left" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-12"><label>Filters:</label></div>
                    <div class="col-xs-12 col-sm-2">
                        <select v-model="activeFilter" class="form-control" v-on:change="changeActiveFilter">
                            <option value="all">All</option>
                            <option value="active">Active</option>
                            <option value="deactive">Deleted</option>

                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(page, pageIndex) in pages.data">
                        <td>{{ page.title }}</td>
                        <td>
                            <div v-if="checkTrashed(page)">
                                <a title="Details" @click.prevent="detailItem(page,pageIndex)" class="btn btn-info btn-sm">View</a>
                                <a title="Edit" @click.prevent="editItem(page,pageIndex)" class="btn btn-info btn-sm">Edit</a>
                                <a title="Delete" href="javascript:void(0);" @click.prevent="destroy(pageIndex, page.id)" class="btn btn-danger btn-sm">Delete</a>
                            </div>
                            <div v-else>
                                <a title="Restore" href="javascript:void(0);" @click.prevent="restore(pageIndex, page.id)" class="btn btn-info btn-sm">Restore</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                      <th>Title</th>
                      <th>Action</th>
                    </tr>
                </tfoot>
            </table>
            <nav class="pull-right">
                <ul class="pagination">
                    <li v-if="pagination.current_page > 1">
                        <a href="#" aria-label="Previous"
                           @click.prevent="changePage(pagination.current_page - 1)">
                            <span aria-hidden="true">Previous</span>
                        </a>
                    </li>
                    <li v-for="page in pagesNumber"
                        v-bind:class="[ page == isActived ? 'active' : '']">
                        <a href="#"
                           @click.prevent="changePage(page)">{{ page }}</a>
                    </li>
                    <li v-if="pagination.current_page < pagination.last_page">
                        <a href="#" aria-label="Next"
                           @click.prevent="changePage(pagination.current_page + 1)">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Post</h4>
                    </div>
                    <div class="modal-body">

                        <form method="POST" v-on:submit.prevent="createItem">
                            <div class="form-group">
                              <label for="name">Title:</label>
                              <input type="text" name="title" class="form-control" v-model="newItem.title" />
                              <span v-if="formErrors['title'] " class="error text-danger">{{ formErrors['title'] }}</span>
                            </div>
                           <div class="form-group">
                              <label for="description">Description:</label>
                              <textarea name="description" rows="10" class="form-control editor" v-model="newItem.description"></textarea>
                              <span v-if="formErrors['description'] " class="error text-danger">{{ formErrors['description'] }}</span>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Post</h4>
                    </div>
                    <div class="modal-body">

                        <form method="POST" v-on:submit.prevent="updateItem(fillItem.id)">
                            
                            <div class="form-group">
                              <label for="name">Title:</label>
                              <input type="text" name="title" class="form-control" v-model="fillItem.title" />
                              <span v-if="formErrorsUpdate['title'] " class="error text-danger">{{ formErrorsUpdate['title'] }}</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description"  rows="10" class="form-control" v-model="fillItem.description"></textarea>
                                <span v-if="formErrorsUpdate['description'] " class="error text-danger">{{ formErrorsUpdate['description'] }}</span>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detail-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Post details</h4>
                        <hr>
                        <h5 class="modal-title" id="myModalLabel">{{ item.title }}</h5>
                    </div>
                    <div class="modal-body">
                      <div>{{ item.description}}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                pages: {
                    data: []
                },
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset:4,
                loaded: false,
                formErrors: {},
                formErrorsUpdate: {},
                activeFilter:'active',
                newItem: {'title': '','description':''},
                fillItem: {'title': '','description':''},
                item: {'title': '','description':''},
            }
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                 if (!this.pagination.to) {
                     return [];
                 }
                 var from = this.pagination.current_page - this.offset;
                 if (from < 1) {
                     from = 1;
                 }
                 var to = from + (this.offset * 2);
                 if (to >= this.pagination.last_page) {
                     to = this.pagination.last_page;
                 }
                 var pagesArray = [];
                 while (from <= to) {
                     pagesArray.push(from);
                     from++;
                 }
                 return pagesArray;
            },
        },
        methods:{
            changeActiveFilter(event){
                this.activeFilter=event.target.value;
                this.fetchPosts(1);
            },
            changeStatusFilter(event){
                this.statusFilter=event.target.value;
                this.fetchPosts(1);
            },
            checkTrashed(item){
                return (!item.deleted_at || item.deleted_at===null) ? true : false;
            },
            fetchPosts(page) { 
                return this.$http.get(ajaxUrl+'/post/all?page='+page+'&activeFilter='+this.activeFilter).then((response) =>{
                    this.pages = response.body.data;
                    this.loaded = true;
                    this.pagination=response.body.pagination;
                });
            },
            changePage: function (page) {
              this.pagination.current_page = page;
              this.fetchPosts(page);
            },
            createItem: function() {
                var input = this.newItem;
                var form = new FormData();
                for(let [key,value] of Object.entries(input)){
                    form.append(key,value);
                }

                this.$http.post(ajaxUrl+'/post',form).then((response) => {
                    this.newItem = {'name': '','content':''};
                    this.formErrors = {};
                    this.fetchPosts(1);
                    toastr.success('Post Added Successfully.','Success Alert');
                    $('#create-item').hide();
                }, (response) => {
                    this.formErrors = response.data.errors;
                });
            },
            detailItem:function(item,serviceIndex){
                this.item=item;
                $("#detail-item").modal('show');
            },
            editItem: function (item,serviceIndex) {
                this.fillItem.id = item.id;
                this.fillItem.title = item.title;
                this.fillItem.description = item.description;
                this.formErrorsUpdate={};
                $("#edit-item").modal('show');
            },
            updateItem: function (id) {
                var input = this.fillItem;
                var form = new FormData();
                for (let [key, value] of Object.entries(this.fillItem)) {
                    form.append(key,value);
                }
                form.append('_method','patch');
                this.$http.post(ajaxUrl+'/post/' + id, form).then((response) => {
                    this.fillItem = {'name': '', 'content': '','type':0};
                    this.fetchPosts(this.pagination.current_page);
                    toastr.success('Post Updated Successfully.', 'Success Alert');

                    $("#edit-item").modal('hide');
                }, (response) => {
                    this.formErrorsUpdate = response.data.errors;
                });
            },
            destroy(serviceIndex, id) {
              if(confirm('Are you sure delete this record?')){
                return this.$http.delete(ajaxUrl+'/post/' + id).then((response) => {
                    let from = this.pagination.from;
                    let to = this.pagination.to;

                    if(this.pagination.last_page==this.pagination.current_page && from==to){
                        this.fetchPosts(1);
                    }else{
                        this.fetchPosts(this.pagination.current_page);
                    }
                    toastr.success('Post deleted successfully');
                });
              }
            },
            restore(faqIndex, id){
                return this.$http.get(ajaxUrl+'/post/' + id + '/restore').then((response) => {
                    let from = this.pagination.from;
                    let to = this.pagination.to;

                    if(this.pagination.last_page==this.pagination.current_page && from==to){
                        this.fetchPosts(1);
                    }else{
                        this.fetchPosts(this.pagination.current_page);
                    }
                    toastr.success('Post restore successfully');
                });
            },
        },
        mounted() {
            this.fetchPosts(1);
        }
    }
</script>