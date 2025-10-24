                            </div>
                        </div>
                    </div>
                </div> 
            </div> 

<?php
if (!function_exists('is_turbolinks_request')) {
    function is_turbolinks_request() {
        return isset($_SERVER['HTTP_TURBOLINKS_REFERRER']);
    }
}

if (!is_turbolinks_request()) { 
?>
            <footer class="footer">
                <?= date("Y") ?> © wamdha
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- jQuery  -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>

    <!-- App js -->
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        document.addEventListener('turbolinks:load', function() {
            if ($('#example').length) { 
                $('#example').DataTable();
            }
            
        });
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turbolinks/5.2.0/turbolinks.js" defer></script>

</body>
</html>
<?php 
} 
?>