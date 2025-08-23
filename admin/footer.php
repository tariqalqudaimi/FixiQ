                            </div><!-- this closes the card-box div -->
                        </div><!-- this closes the col-12 div -->
                    </div><!-- this closes the row div -->
                </div> <!-- this closes the container-fluid div -->
            </div> <!-- this closes the content div -->

<?php
// Define the helper function again here in case a page is loaded that doesn't include the header.
if (!function_exists('is_turbolinks_request')) {
    function is_turbolinks_request() {
        return isset($_SERVER['HTTP_TURBOLINKS_REFERRER']);
    }
}

// THIS IS THE MAIN CHANGE: We only show the full footer if it's NOT a Turbolinks request.
if (!is_turbolinks_request()) { 
?>
            <footer class="footer">
                <?= date("Y") ?> © Malik Niyaz
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    
    <!-- App js -->
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- This script tag will now handle initializing plugins like DataTables -->
    <script>
        // THIS IS THE NEW WAY TO RUN JAVASCRIPT
        // It runs on the first page load AND every time Turbolinks loads a new page.
        document.addEventListener('turbolinks:load', function() {
            // Your DataTables initialization
            // This 'if' statement prevents errors if you go to a page without a table
            if ($('#example').length) { 
                $('#example').DataTable();
            }
            
            // If you find other JS plugins stop working, you initialize them here too.
        });
    </script>
    
    <!-- NEW: Turbolinks script added at the end with the 'defer' attribute -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turbolinks/5.2.0/turbolinks.js" defer></script>

</body>
</html>
<?php 
} // End the PHP if block. This is important!
?>