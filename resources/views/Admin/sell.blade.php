@extends('layouts.layout_admin_lte')

@section('css')
  <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
@endsection

@section('content_header','Produk')

@section('content')
  <!--Modal Tambah Gambar-->
  <div id="tambah_gambar" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Tambah Gambar</h4>
  			</div>
  			<div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label></label>
              <form action="{{url('/admin/add_product_image')}}" class="dropzone" id="my-dropzone">
                <div class="dz-message"><h1><i class="fa fa-photo"></i></h1>Drag dan Drop atau klik disini untuk upload (max:10 gambar)</div>
                {{csrf_field()}}
                <input type="hidden" name="product_id" value="">
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" style="text-align:center">
              <button id="submit-all" style="display:none" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
              <button type="reset" style="display:none" class="btn btn-danger" id="reset"><i class="fa fa-close"></i> Batal</button>
            </div>
          </div>
  			</div>
  			<div class="modal-footer">
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
  <!--End Modal Tambah Gambar-->

  <!--Modal Edit produk-->
  <div id="edit" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Edit produk</h4>
  			</div>
  			<div class="modal-body">
          @if (Session::has('status_edit_product'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_edit_product')}}
            </div>
          @endif
          <form id="edit_form" action="{{url('/admin/edit_product/')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="product_id" class="form-control" placeholder="product_id"><br>
              <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                <label for="product_name" class="control-label">Nama Produk</label>
                <input type="text" name="product_name" class="form-control" placeholder="Nama produk" required>
                @if ($errors->has('product_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('product_name') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                <label class="control-label">Kategori</label>
                <select class="form-control" name="category_id" required>
                  <option value="" disabled selected>Pilih category</option>
                  @foreach ($category as $c)
                    <option value="{{$c->category_id}}">{{$c->category_name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('category_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_id') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group{{ $errors->has('store_id') ? ' has-error' : '' }}">
                <label class="control-label">Pilih Toko</label>
                <select class="form-control" name="store_id" required>
                  <option value="" disabled selected>Pilih Toko</option>
                  @foreach ($store as $s)
                    <option value="{{$s->store_id}}">{{$s->store_name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('store_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('store_id') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label class="control-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="8" style="resize:none" placeholder="Deskripsi" required></textarea>
                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                <label class="control-label">Berat (gram)</label>
                <input type="number" name="weight" min="0" class="form-control" placeholder="Stok" required>
                @if ($errors->has('weight'))
                    <span class="help-block">
                        <strong>{{ $errors->first('weight') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
                <label class="control-label">Stok</label>
                <input type="number" name="stock" min="0" class="form-control" placeholder="Stok" required>
                @if ($errors->has('stock'))
                    <span class="help-block">
                        <strong>{{ $errors->first('stock') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('first_price') ? ' has-error' : '' }}">
                <label class="control-label">Harga Awal</label>
                <input type="number" name="first_price" min="0" class="form-control" placeholder="Harga" required>
                @if ($errors->has('first_price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_price') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                <label class="control-label">Harga Akhir</label>
                <input type="number" name="price" min="0" class="form-control" placeholder="Harga" required>
                @if ($errors->has('price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                <label class="control-label">Meta Keyword</label>
                <textarea name="meta_keyword" class="form-control" placeholder="Meta Keyword" required></textarea>
                @if ($errors->has('meta_keyword'))
                    <span class="help-block">
                        <strong>{{ $errors->first('meta_keyword') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                <label class="control-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" placeholder="Meta Description" required></textarea>
                @if ($errors->has('meta_description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('meta_description') }}</strong>
                    </span>
                @endif
              </div>
  			</div>
  			<div class="modal-footer">
  					<button type="submit" class="btn btn-success">Edit</button>
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
  <!--End Modal Edit produk-->

  <!--Modal Hapus produk-->
  <div id="hapus" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Hapus produk</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus produk:
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="{{url('/admin/delete_product/')}}">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>
<!--End Modal Hapus produk-->


  <div class="col-md-12">
      <div class="box box-primary">
          <div class="box-header with-border"><h4 class="box-title">Jual produk</h4></div>
          <div class="box-body">
              @if (Session::has('status_input_product'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_input_product')}}
                </div>
              @endif
              @if (Session::has('status_delete_product'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_delete_product')}}
                </div>
              @endif
              <form action="{{url('/admin/add_product/')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                    <label class="control-label">Nama Produk</label>
                    <input type="text" name="product_name" class="form-control" placeholder="Nama produk" value="{{old('product_name')}}">
                    @if ($errors->has('product_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('product_name') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                    <label class="control-label">Kategori</label>
                    <select class="form-control" name="category_id">
                      <option value="" disabled selected>Pilih Kategori</option>
                      @foreach ($category as $c)
                        <option value="{{$c->category_id}}">{{$c->category_name}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('category_id') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('store_id') ? ' has-error' : '' }}">
                    <label class="control-label">Pilih Toko</label>
                    <select class="form-control" name="store_id">
                      <option value="" disabled selected>Pilih Toko</option>
                      @foreach ($store as $s)
                        <option value="{{$s->store_id}}">{{$s->store_name}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('store_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('store_id') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label class="control-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="8" style="resize:none" placeholder="Deskripsi">{{old('decription')}}</textarea>
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                    <label class="control-label">Berat (gram)</label>
                    <input type="number" name="weight" class="form-control" min="0" placeholder="Berat(gram)">
                    @if ($errors->has('weight'))
                        <span class="help-block">
                            <strong>{{ $errors->first('weight') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
                    <label class="control-label">Stok</label>
                    <input type="number" name="stock" min="0" class="form-control numberOnly" placeholder="Stok" value="{{old('stock')}}">
                    @if ($errors->has('stock'))
                        <span class="help-block">
                            <strong>{{ $errors->first('stock') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('first_price') ? ' has-error' : '' }}">
                    <label class="control-label">Harga Awal (Rp) Optional</label>
                    <input type="number" name="first_price" min="0" class="form-control" placeholder="Harga" value="{{old('first_price')}}">
                    @if ($errors->has('first_price'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_price') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label class="control-label">Harga Akhir (Rp)</label>
                    <input type="number" name="price" min="0" class="form-control" placeholder="Harga" value="{{old('price')}}">
                    @if ($errors->has('price'))
                        <span class="help-block">
                            <strong>{{ $errors->first('price') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                    <label class="control-label">Meta Keyword</label>
                    <textarea name="meta_keyword" class="form-control" placeholder="Meta Keyword">{{old('meta_keyword')}}</textarea>
                    @if ($errors->has('meta_keyword'))
                        <span class="help-block">
                            <strong>{{ $errors->first('meta_keyword') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                    <label class="control-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" placeholder="Meta Description">{{old('meta_description')}}</textarea>
                    @if ($errors->has('meta_description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('meta_description') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                    <label class="control-label">Gambar</label>
                    <input type="file" name="file" id="file" value="{{old('file')}}" required>
                    @if ($errors->has('file'))
                        <span class="help-block">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
              </form>
          </div>
      </div>
  </div>

  <div class="col-md-12">
      <div class="box box-primary">
          <div class="box-header with-border"><h4 class="box-title">Daftar produk</h4></div>
          <div class="box-body">
            <table class="table table-responsive table-hovered table-bordered" id="example2">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama produk</th>
                  <th>Gambar produk</th>
                  <th>Deskripsi</th>
                  <th>Berat (kg)</th>
                  <th>Stok</th>
                  <th>Harga (Rp)</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php($n=1)
                @foreach ($products as $b)
                  <tr>
                    <td>{{$n++}}</td>
                    <td>{{$b->product_name}}</td>
                    <td>
                      @if (count($b->product_images)!=0)
                        @foreach ($b->product_images->take(1) as $g)
                          <img src="/uploads/gambar_produk/{{$user->user_id}}_{{$user->profile->first_name}}/{{"produk".$b->product_id}}/{{$g->product_image_name}}" width="200px">
                        @endforeach
                        <br> {{count($b->product_images)}} gambar
                      @else
                        Belum ada gambar
                      @endif
                    </td>
                    <td>{{$b->description}}</td>
                    <td>{{$b->weight/1000}} kg</td>
                    <td>{{number_format($b->stock)}}</td>
                    <td>{{number_format($b->price)}}</td>
                    <td>
                      {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_gambar" onclick="ajax_ed('tambah_gambar',{{$b->product_id}})"><i class="fa fa-image"></i></button> --}}
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed('edit',{{$b->product_id}})"><i class="fa fa-edit"></i></button>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus" onclick="ajax_ed('hapus',{{$b->product_id}})"><i class="fa fa-close"></i></button>
                      <a href="{{url('/admin/product_detail/'.$b->product_id)}}" class="btn btn-default"><i class="fa fa-bar-chart"></i>Detail</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
  </div>
@endsection

@section('js')
  <script src="{{ asset('js/dropzone.js') }}"></script>

  <script type="text/javascript">

      $(document).ready(function() {
      $(".numberOnly").keydown(function (e) {
          // Allow: backspace, delete, tab, escape, enter and .
          if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
               // Allow: Ctrl/cmd+A
              (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
               // Allow: Ctrl/cmd+C
              (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
               // Allow: Ctrl/cmd+X
              (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
               // Allow: home, end, left, right
              (e.keyCode >= 35 && e.keyCode <= 39)) {
                   // let it happen, don't do anything
                   return;
          }
          // Ensure that it is a number and stop the keypress
          if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
          }
      });
    });

    @if (Session::has('status_edit_product'))
      $(window).on('load',function(){
          $('#edit').modal('show');
          ajax_ed('edit',{{Session::get('product_id')}});
      });
    @endif

    function ajax_ed(jenis,id){
      $.ajax({
        type:"get",
        url:"/admin/ajax_ed",
        data:{'id':id},
        success:function(data){
          if(jenis=="edit"){
            p_edit(id,data);
          }else if(jenis=="hapus"){
            p_delete(id,data);
          }else{
            p_tambah_gambar(id,data);
          }
        },
        error:function(data){
          alert('error '+data);
        }
      });
    }

    function p_edit(id,data){
      var products=JSON.parse(data);
      var product_name=products['product_name'];
      var category_id=products['category_id'];
      var store_id=products['store_id'];
      var description=products['description'];
      var weight=products['weight'];
      var stock=products['stock'];
      var price=products['price'];
      var first_price=products['first_price'];
      var meta_keyword=products['meta_keyword'];
      var meta_description=products['meta_description'];

      $('#edit [name="product_id"]').val(id);
      $('#edit [name="product_name"]').val(product_name);
      $('#edit [name="category_id"]').val(category_id);
      $('#edit [name="description"]').val(description);
      $('#edit [name="store_id"]').val(store_id);
      $('#edit [name="weight"]').val(weight);
      $('#edit [name="stock"]').val(stock);
      $('#edit [name="first_price"]').val(first_price);
      $('#edit [name="price"]').val(price);
      $('#edit [name="meta_keyword"]').val(meta_keyword);
      $('#edit [name="meta_description"]').val(meta_description);
    }

    function p_delete(id,data){
      var products=JSON.parse(data);
      var product_name=products['product_name'];

      $('#hapus .modal-body').html("Apakah Anda yakin ingin menghapus produk: <label class='label label-danger'>"+product_name+"</label>");
      $('#hapus .modal-footer .btn-hapus').attr('href','/admin/delete_product/'+id);
    }

    function p_tambah_gambar(id,data){
      var products=JSON.parse(data);
      var product_name=products['product_name'];

      $('#tambah_gambar label').text("Gambar untuk "+product_name);
      $('#tambah_gambar input[name="product_id"]').val(id);
    }

    //Dropzone

  		Dropzone.options.myDropzone = {

  		  // Prevents Dropzone from uploading dropped files immediately
  		  autoProcessQueue: false,
  		  acceptedFiles: "image/*",
        parallelUploads:10,
  		  init: function() {
  			var submitButton = document.querySelector("#submit-all")
  				myDropzone = this; // closure

  			submitButton.addEventListener("click", function() {
  			  myDropzone.processQueue(); // Tell Dropzone to process all queued files.
  			});

  			var cancelButton=document.querySelector("#reset")
  				myDropzone=this;

  			cancelButton.addEventListener("click",function(){
  				myDropzone.removeAllFiles(true);
  				$("#submit-all").hide();
  				$("#reset").hide();
  			});
        myDropzone.options.url="/admin/add_product_image";
  			// You might want to show the submit button only when
  			// files are dropped here:
  			this.on("addedfile", function() {
  				$("#submit-all").show();
  				$("#reset").show();
  			});

  		  }
  		};
  	//Dropzone

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  </script>
@endsection
