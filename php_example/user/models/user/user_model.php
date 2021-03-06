<?php
include $_SESSION['BASE_PATH']."/models/connect_to_database_server.php";
class User{
  public function get_info_user_by_username( $username ){
      global $conn;
      $query = "select MSSV as username, MatKhau as password, HoTen as fullname
					 from sinhvien
					 where MSSV = '$username';";
           return mysqli_query($conn,$query);
         }
  public function changepassword_user ( $username, $newpassword ){
            global $conn;
           $query = "update sinhvien
					 set MatKhau = '$newpassword'
					 where MSSV = '$username';";
           return mysqli_query($conn,$query);
         }
  public function get_common_info_by_username ( $username  ){
            global $conn;
           $query = "select HoTen as fullname, MSSV as username, NgaySinh as birth, GioiTinh as sex, QueQuan as native_land, sinhvien.KhoaHoc as course, TenLopSV as class, ChTrinh as training_system, HeHoc as level_school
					 from sinhvien JOIN lopsv
					 where MSSV = '$username';";
           return mysqli_query($conn,$query);
         }
  public function get_timetable_by_username( $username ){
            global $conn;
           $query = "select NgayHoc as day_of_week, GioHoc as time, TuanHoc as week, PhongHoc as place, LopHP.MaHP as id_subject , HocPhan.TenHP as name_subject
					 from thoigianhoc natural join sinhvien natural join lophpdk natural join lophp natural join hocphan
					 where sinhvien.MSSV = '".$username."'
					 order by NgayHoc asc ;";
           return mysqli_query($conn,$query);
         }
  public function get_all_require_subject ( $username ){
            global $conn;
           $query = "select chuongtrinh.MaHP as id_subject, hocphan.TenHP as name_subject, chuongtrinh.HocKy as semester, hocphan.Sotinchi as credits_subject, khoavien.TenKhoa as course
                      from sinhvien JOIN lopsv, khoavien, hocphan JOIN chuongtrinh
                      where lopsv.ChTrinh=chuongtrinh.ChTrinh and khoavien.MaKhoa = hocphan.MaKhoa and MSSV = '".$username."'
                      order by chuongtrinh.HocKy asc ;";
          return mysqli_query($conn,$query);
         }
  public function get_table_point ( $username ) {
            global $conn;
           $query = "select HocKy as semester, hocphan.MaHP as 	id_subject, TenHP as name_subject, Sotinchi as  credits_subject, lophp.MaLopHP as id_class, DiemQT as mid_point, DiemCK as end_point, XepLoai as  point_by_string
					 from ketqua natural join lophp natural join hocphan
					 where MSSV = '".$username."'
					 order by HocKy asc;";
           return mysqli_query($conn,$query);
         }
  public function report_study ( $username ) {
            global $conn;
           $query = "select HocKy as semester, TCdangky as signed_subject, TCtichluy as passed_subject, GPA as agv_point_semester, CPA as avg_point, TrinhDo as level, LopSV.ChTrinh as course
					 from sinhvien natural join lopsv natural join tichluy
					 where tichluy.MSSV = '".$username."' ;";
           return mysqli_query($conn,$query);
         }
  public function find_student ( $username ){
            global $conn;
           $query = "select MSSV as username, HoTen as fullname, NgaySinh as birth,GioiTinh as sex, QueQuan as native_land, TenLopSV as class, LopSV.KhoaHoc as khoa_hoc, ChTrinh as system_training
					 from sinhvien JOIN lopsv
					 where MSSV = '".$username."' ;";
           return mysqli_query($conn,$query);
         }
  public function get_list_singed_subject_by_semester_username ( $semester, $username ) {
            global $conn;
           $query = "select LopHP.MaLopHP as id_class, HocPhan.MaHP as id_subject,TenHP as name_subject, LopHPDK.TrangThai as status_sign
					 from lophpdk, lophp, hocphan
					 where lophpdk.MaLopHP = lophp.MaLopHP and lophp.MaHP = hocphan.MaHP and lophpdk.TrangThai = 'Thành Công' and lophpdk.MSSV ='".$username."' and lophp.HocKy = '".$semester."' ;";
           return mysqli_query($conn,$query);
         }
  public function get_system_training_by_username( $username ){
            global $conn;
           $query = "select ChTrinh as training_system
					 from sinhvien JOIN lopsv
					 where MSSV = '".$username."' ;";
           return mysqli_query($conn,$query);
         }
  public function find_student_by_name_khoasv_course_lop( $name_find, $khoasv_find, $course_find, $lop_find){
            global $conn;
           if ( ($name_find != "*") || ($khoasv_find != "*") || ( $course_find != "*") || ( $lop_find != "*")){
             $query = "select  MSSV as username, HoTen as fullname, NgaySinh as birth,GioiTinh as sex, QueQuan as native_land, TenLopSV as class, LopSV.KhoaHoc as khoa_hoc, ChTrinh as system_training
					   from sinhvien JOIN lopsv JOIN khoavien
					   where ";
             if ( $khoasv_find != "*" ){
               $query = $query." KhoaHoc = '".$khoasv_find."' and ";
               if( $name_find != "*"){
                 $query = $query."HoTen = '".$name_find."' and ";
               }
               if( $course_find != "*"){
                 $query = $query." TenKhoa = '".$course_find."' and ";
               }
               if($lop_find != "*"){
                 $query = $query." TenLopSV = '".$lop_find."' and ";
               }
               $query = $query." true;";
             } else {
               $query = $query." false ;";
             }
           } else {
               $query = "select MSSV as username, HoTen as fullname, NgaySinh as birth,GioiTinh as sex, QueQuan as native_land, TenLopSV as class, LopSV.KhoaHoc as khoa_hoc, ChTrinh as system_training
						 from sinhvien JOIN lopsv JOIN khoavien
						 where false ;";
             }
           return mysqli_query($conn,$query);
         }
  public function get_sum_credits_subject_by_semester_username( $semester, $username){
            global $conn;
           $query = "select COALESCE(sum(Sotinchi),0) as sum
					 from lophp natural join hocphan left join lophpdk on lophpdk.MaLopHP = lophp.MaLopHP
					 where HocKy = '".$semester."' and MSSV = '".$username."' ;";
           return mysqli_query($conn,$query);
         }
    public function get_list_register_class_by_username_semester( $username, $semester){
            global $conn;
           $query = "select lophp.MaLopHP as id_class, hocphan.MaHP as id_subject, TenHP as name_subject, lophp.TrangThai as status, lophpdk.TrangThai as register_status, Sotinchi as credits_subject
					 from lophp natural join hocphan left join lophpdk on lophpdk.MaLopHP = lophp.MaLopHP
					 where MSSV = '".$username."' and HocKy = '".$semester."' ;";
           return mysqli_query($conn,$query);
         }
  public function register_class_by_username_id_class( $username, $id_class){
                    global $conn;
		   $query = "insert into lophpdk
					 values ('".$id_class."', '".$username."', 'Chờ đăng ký') ;";
           return mysqli_query($conn,$query);
         }
  public function unregister_class_by_username_id_class( $username, $id_class){
                global $conn;
             $query = "delete from lophpdk
					   where MSSV = '".$username."' and MaLopHP = '".$id_class."' ;";
           return mysqli_query($conn,$query);
         }
  public function submit_register_class_by_username( $username){
             global $conn;
             $query = "update lophpdk
					   set TrangThai = 'Thành Công'
					   where MSSV = '".$username."' ;";
           return mysqli_query($conn,$query);
         }

}

?>
