<?php
namespace App\Libraries;
/**
 * Nota de HTML:
 * Paginación con bootstrap v5.1.3
 * La paginación de bootstrap no es responsiva en esta versión, 
 * por lo que es necesario agregar la siguiente clase.
 * .pagination {display: -ms-flexbox;flex-wrap: wrap;
 * display: flex;padding-left: 0;list-style: none;
 * border-radius: 0.25rem;}
 * 
 * Implementación:
 * $totalCantReg = Usuario::cantidadRegistros();
 * $enlacePaginacion = URLROOT.'/usuarios/listar/';
 * $_Paginacion = Paginacion::load($enlacePaginacion, $totalCantReg, $numeroPagina);
 * if (!$_Paginacion instanceof $_Paginacion) throw new \Exception($_Paginacion);
 * $aFiltros = ['limite' => "{$_Paginacion->inicioLimite} , {$_Paginacion->cantidadRegPorPagina}"];
 * 
 * Implementación a nivel vista:
 * <?php $_Paginacion->cargaHtmlPaginacion(); ?>
 */
class Paginacion {
    /**
     * Cantidad de registros a mostrar por página.
     * @var integer
     */
    public $totalRegPorPagina;
    /**
     * Número de página a calcular.
     * @var integer
     */
    public $numeroPagina;
    /**
     * Cantidad total de registros encontrados 
     * (normalmente obtenido por consulta).
     * @var integer
     */
    public $totalCantidadRegistros;
    /**
     * Inicio límite de página.
     * Si se ocupa en consulta: LIMIT $inicioLimite, 30
     * @var integer
     */
    public $inicioLimite;
    /**
     * Cantidad de registros por página.
     * Si se ocupa en consulta: LIMIT 20, $cantidadRegPorPagina
     * @var integer
     */
    public $cantidadRegPorPagina;

    /**
     * Asigna valor a los propiedades.
     * @param string $enlacePaginacion Enlace ó url de la vista 
     * de la paginación. Ej: http://ejemplo/ejemplo/ejemplo/4
     * @param integer $totalCantidadRegistros Cantidad total de registros 
     * encontrados (normalmente obtenido por consulta).
     * @param integer $numeroPagina Número de paginación.
     * @param integer $cantidadRegPorPagina Cantidad de registros por pagína
     * a mostrar.
     */
    private function __construct(string $enlacePaginacion, int $totalCantidadRegistros, int $numeroPagina = 1, int $cantidadRegPorPagina = 10) {
        $inicioLimite = 0;
        $totalRegPorPagina = ceil($totalCantidadRegistros / $cantidadRegPorPagina);
        if ($numeroPagina > 1) {
            $inicioLimite = ($numeroPagina - 1) * $cantidadRegPorPagina;
        }
        if ($numeroPagina > $totalRegPorPagina && $totalRegPorPagina > 0) {
            $numeroPagina = $totalRegPorPagina;
            $inicioLimite = ($numeroPagina - 1) * $cantidadRegPorPagina;
        }
        $this->totalRegPorPagina = $totalRegPorPagina;
        $this->numeroPagina = $numeroPagina;
        $this->totalCantidadRegistros = $totalCantidadRegistros;
        $this->inicioLimite = $inicioLimite;
        $this->cantidadRegPorPagina = $cantidadRegPorPagina;
        $this->enlacePaginacion = $enlacePaginacion;
    }

    /**
     * Retorna instancia Paginacion.
     * @param string $enlacePaginacion Enlace ó url de la vista 
     * de la paginación. Ej: http://ejemplo/ejemplo/ejemplo/4
     * @param integer $totalCantidadRegistros Cantidad total de registros 
     * encontrados (normalmente obtenido por consulta).
     * @param integer $numeroPagina Número de paginación.
     * @param integer $cantidadRegPorPagina Cantidad de registros por pagína
     * a mostrar.
     * @return Paginacion|string string: Mensaje de validación.
     */
    public static function load(string $enlacePaginacion, int $totalCantidadRegistros, $numeroPagina = 1, int $cantidadRegPorPagina = 10) {
        try {
            if ($totalCantidadRegistros < 0) $totalCantidadRegistros = 0;
            $numeroPagina = filter_var($numeroPagina, FILTER_VALIDATE_INT);
            if ($numeroPagina <= 0) $numeroPagina = 1;
            return new self($enlacePaginacion, $totalCantidadRegistros, $numeroPagina, $cantidadRegPorPagina);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Carga html de la paginación.
     * @return mixed
     */
    public function cargaHtmlPaginacion() {
        ob_start();
        $total_pags = $this->totalRegPorPagina;
        $num_pagina = $this->numeroPagina;
        $numTotalReg = $this->totalCantidadRegistros;
        if ($total_pags > 0): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-sm justify-content-end">
                    <li class="page-item">
                        <a class="page-link text-dark" href="<?=$this->enlacePaginacion?>1" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php if ($num_pagina != 1): $pagAnt = $num_pagina - 1; ?>
                        <li class="page-item">
                            <a class="page-link text-dark" href="<?=$this->enlacePaginacion?><?=$pagAnt?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                    $pagI = $num_pagina - 5;
                    $pagF = $num_pagina + 5;
                    if ($pagI < 1) $pagI = 1;
                    if ($pagF > $total_pags) $pagF = $total_pags;
                    foreach (range($pagI, $pagF) as $i):
                        if ($num_pagina == $i): ?>
                            <li class="page-item page-item active" aria-current="page">
                                <a class="page-link text-white">
                                    <?=$num_pagina?>
                                </a>
                            </li><?php
                        else: ?>
                            <li class="page-item">
                                <a class="page-link text-dark" href="<?=$this->enlacePaginacion?><?=$i?>">
                                    <?=$i?>
                                </a>
                            </li><?php
                        endif;
                    endforeach;
                    ?>
                    <?php if ($num_pagina != $total_pags): $pagSig = $num_pagina +1; ?>
                        <li class="page-item">
                            <a class="page-link text-dark" href="<?=$this->enlacePaginacion?><?=$pagSig?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link text-dark" href="<?=$this->enlacePaginacion?><?=$total_pags?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link text-dark" title="Total de registros encontrados">
                            <?=$numTotalReg?>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
        endif;
        echo ob_get_clean();
    }
}