@if ($errors->any())
<div class="alert alert-danger bg-danger animated bounceInRight shadow-lg" style="
    width: 50%;
    position: fixed;
    bottom: 0;
    right: 5px;
    z-index: 1;
">
    <button type="button" class="close" data-dismiss="alert" aria-label="Clise">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="row">
        <div class="col-md-1">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x"></i>
            </div>
        </div>
        <div class="col-md-11">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if (session('success'))
    <div id="alert-msg" class="alert alert-success bg-success animated bounceInRight shadow-lg" style="
    width: 15%;
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 1;
    ">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <i class="fas fa-check fa-2x mb-2"></i>
                    <br>
                    {{ session('success') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger bg-danger animated bounceInRight shadow-lg" style="
    width: 50%;
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 1;
    ">
        <button type="button" class="close" data-dismiss="alert" aria-label="Clise">
            <span aria-hidden="true">&times;</span>
        </button>
       {{ session('error') }}
    </div>
@endif

<script>
    setTimeout(
        function () {
            $('#alert-msg').show().addClass('animated bounceOutRight');
        }, 2500
    );
</script>