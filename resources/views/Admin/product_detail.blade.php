@extends('layouts.layout_admin_lte')

@section('content_header','Detail Produk')

@section('css')
  <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
@endsection

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
                <div class="dz-message"><h1><i class="fa fa-photo"></i></h1>Drag dan Drop atau klik disini untuk upload (max:{{5-count($product->product_images)}} gambar)</div>
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

<div id="delete_image" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hapus Gambar</h4>
      </div>
      <div class="modal-body">
        Apakah anda yakin ingin menghapus gambar ini?
      </div>
      <div class="modal-footer">
          <a href="{{url('/admin/delete_product_image/')}}" class="btn btn-danger btn-hapus"><i class="fa fa-close"></i> Hapus Gambar</a>
          <button class="btn btn-default" data-dismiss="modal">Batal</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
          <div class="box-header with-border"><h4 class="box-title">Detail Produk {{$product->product_name}}</h4></div>
          <div class="box-body">
            @if (Session::has('status_delete'))
              <div class="row">
                <div class="col-md-8">
                  <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_delete')}}
                  </div>
                </div>
              </div>
            @endif
            <div class="row">
              <div class="col-md-6" style="padding-left:80px">
                @if (count($product->product_images))
                  @php($path="/uploads/gambar_produk/".$product->seller->user_id."_".$product->seller->profile->first_name."/produk".$product->product_id)
                  @foreach ($product->product_images->take(1) as $g)
                    <div class="product-image__big__items">
                        <div class="product-image__ratio">
                          <img src="{{$path."/".$g->product_image_name}}" width="400px">
                        </div>
                    </div>
                  @endforeach
                @endif
              </div>
              <div class="col-md-6">
                <table class="table table-striped">
                  <tr>
                    <th>Nama Produk</th>
                    <td>: {{$product->product_name}}</td>
                  </tr>
                  <tr>
                    <th>Kategori</th>
                    <td>: {{$product->category->category_name}}</td>
                  </tr>
                  <tr>
                    <th>Toko</th>
                    <td>: {{$product->store->store_name}}</td>
                  </tr>
                  <tr>
                    <th>Penjual</th>
                    <td>: {{$product->seller->profile->first_name." ".$product->seller->profile->last_name}}</td>
                  </tr>
                  <tr>
                    <th>Berat</th>
                    <td>: {{number_format($product->weight)}} gram</td>
                  </tr>
                  <tr>
                    <th>Harga</th>
                    <td>: Rp {{number_format($product->price)}}</td>
                  </tr>
                  <tr>
                    <th>Stok</th>
                    <td>: {{number_format($product->stock)}}</td>
                  </tr>
                  <tr>
                    <th>Terjual</th>
                    <td>: {{$product->cart->where('status','1')->sum('amount')}}x</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="box box-success">
          <div class="box-header with-border">
            <h4 class="box-title">Gambar Produk</h4>
            @if (5-count($product->product_images)>0)
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_gambar" onclick="ajax_ed('tambah_gambar',{{$product->product_id}})"><i class="fa fa-image"></i></button>
            @endif
          </div>
          <div class="box-body">
            <table class="table table-striped" id="example2">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Gambar</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody>
                @php($salim=1)
                @foreach ($product->product_images as $i)
                  <tr>
                    <td>{{$salim++}}</td>
                    <td><img src="{{$path."/".$i->product_image_name}}" width="200px"></td>
                    <td>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_image" onclick="delete_image({{$i->product_image_id}})"><i class="fa fa-close"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });

  function delete_image(id){
    $('#delete_image .btn-hapus').attr('href','/admin/delete_product_image/'+id);
  }


  function ajax_ed(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed",
      data:{'id':id},
      success:function(data){
        p_tambah_gambar(id,data);
      },
      error:function(data){
        alert('error '+data);
      }
    });
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
      parallelUploads:{{5-count($product->product_images)}},
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
</script>
@endsection
