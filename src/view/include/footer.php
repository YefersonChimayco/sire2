    </div> <!-- Cierre del page-content -->
    </div> <!-- Cierre del container-fluid -->

    <!-- FOOTER MINIMALISTA -->
    <footer class="footer mt-5">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-left">
                        <span class="text-muted">2025 © SIRE - Sistema De Regristro y de Gestión Institucional</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-right text-md-right">
                        <span class="text-muted">Desarrollado por </span>
                        <span class="developer">Chimayco Carabajal Yeferson</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    </div> <!-- Cierre del main-content -->
    </div> <!-- Cierre del layout-wrapper -->

    <script>
        setTimeout(500);
    </script>

    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/jquery.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/waves.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/simplebar.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.bootstrap4.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.html5.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.flash.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.print.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.select.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/pdfmake.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/vfs_fonts.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/pages/datatables-demo.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/theme.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/js/sesion.js"></script>

    <script src="<?php echo BASE_URL ?>src/view/js/functions_estudiante.js"></script>

    <style>
        /* ===== ESTILOS MINIMALISTAS PARA FOOTER ===== */
        .footer {
            background: var(--text-light);
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 0;
            margin-top: auto;
            font-size: 0.9rem;
        }

        .footer-left,
        .footer-right {
            padding: 0.5rem 0;
        }

        .text-muted {
            color: #6c757d !important;
            font-weight: 400;
        }

        .developer {
            color: var(--secondary-color);
            font-weight: 500;
        }

        /* ===== RESPONSIVE FOOTER ===== */
        @media (max-width: 767.98px) {
            .footer {
                padding: 1rem 0;
            }
            
            .footer-left,
            .footer-right {
                text-align: center !important;
                padding: 0.25rem 0;
            }
            
            .footer .row {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .footer {
                font-size: 0.8rem;
            }
        }
    </style>
</body>
</html>