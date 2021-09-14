<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background-color: white; padding: 5px;">
    <div class="position-sticky pt-3">
        <img src="{{ asset('integracao') }}/img/logo_tascom_escuro.jpg" class="img-fluid" alt="">

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>INTEGRAÇÃO DRG X SOULMV</span>
          </h6>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link {{ $altaActive ?? '' }}" aria-current="page" href="{{ route('integracao') }}">
            <i class="fas fa-user-check" style="color:green"></i>
            &nbsp;Alta
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $admissaoActive ?? '' }}" href="{{ route('admissao') }}">
            <i class="fas fa-file"></i>
            &nbsp;&nbsp;Admissional
          </a>
        </li>
        {{-- <li class="nav-item">
          <a class="nav-link {{ $procedimentosActive ?? '' }}" href="#">
            <i class="fas fa-procedures"></i>
            &nbsp;Procedimentos
          </a>
        </li> --}}
        <li class="nav-item">
          <a class="nav-link {{ $custoActive ?? '' }}" href="{{ route('custo') }}">
            <i class="fas fa-hand-holding-usd"></i>
            &nbsp;Custo
          </a>
        </li>
      </ul>

      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>CONFIGURAÇÕES</span>
      </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
            <a class="nav-link {{ $deparaActive ?? '' }}" aria-current="page" href="{{ route('depara') }}">
                <i class="far fa-handshake"></i>
                &nbsp;DE PARA
            </a>
            </li>
        </ul>
    </div>
  </nav>
