<?php
	if (isset($_POST['themgiohang'])){
		$tensanpham = $_POST['tensanpham'];
		$sanpham_id = $_POST['sanpham_id'];
		$hinhanh = $_POST['hinhanh'];
		$gia = $_POST['giasanpham'];
		$soluong = $_POST['soluong'];
		
		$sql_select_giohang = mysqli_query($mysqli, "SELECT * FROM tbl_giohang WHERE sanpham_id='$sanpham_id'");
		$count = mysqli_num_rows($sql_select_giohang);
		if ($count>0){
			$row_sanpham = mysqli_fetch_array($sql_select_giohang);
			$soluong = $row_sanpham['soluong']+1;
			$sql_giohang ="UPDATE tbl_giohang SET soluong='$soluong' WHERE sanpham_id='$sanpham_id'";
		}else{
			$soluong = $soluong;
			$sql_giohang ="INSERT INTO tbl_giohang(tensanpham, sanpham_id, hinhanh, giasanpham, soluong) VALUES ('$tensanpham','$sanpham_id','$hinhanh','$gia','$soluong')";
		}
		$insert_row = mysqli_query($mysqli, $sql_giohang);
		if ($insert_row==0){
			header('Location: index.php?quanly=chitietsp&id='.$sanpham_id);
		}
	}elseif(isset($_POST['capnhatsoluong'])){ //cap nhat so luong san pham
		for($i=0;$i<count($_POST['product_id']); $i++){
			$sanpham_id = $_POST['product_id'][$i];
			$soluong = $_POST['soluong'][$i];
			if($soluong <= 0){
				$sql_update = mysqli_query($mysqli, "DELETE FROM tbl_giohang WHERE sanpham_id='$sanpham_id'");
			}else{
				$sql_update = mysqli_query($mysqli, "UPDATE tbl_giohang SET soluong='$soluong' WHERE sanpham_id='$sanpham_id'");
			}
		}
	}elseif(isset($_GET['xoa'])){ //xoa gio hang
		$id = $_GET['xoa'];
		$sql_delete = mysqli_query($mysqli, "DELETE FROM tbl_giohang WHERE giohang_id='$id'");
	}elseif(isset($_POST['thanhtoan'])){
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$note = $_POST['note'];
		$address = $_POST['address'];
		$giaohang = $_POST['giaohang'];
		$sql_khachhang = mysqli_query($mysqli,"INSERT INTO tbl_khachhang(name, phone, address, note, email, giaohang) VALUES ('$name','$phone','$address','$note','$email','$giaohang')");
	}
?>

<!-- checkout page -->
<div class="privacy py-sm-5 py-4">
		<div class="container py-xl-4 py-lg-2">
			<!-- tittle heading -->
			<h3 class="tittle-w3l text-center mb-lg-5 mb-sm-4 mb-3">
				Giỏ hàng của bạn
			</h3>
			<!-- //tittle heading -->
			<div class="checkout-right">
				<?php
					$sql_lay_giohang = mysqli_query($mysqli, "SELECT * FROM tbl_giohang ORDER BY giohang_id DESC");

				?>
				<div class="table-responsive">
				<form action="" method="POST">
					<table class="timetable_sub">
						<thead>
							<tr>
								<th>Thứ tự</th>
								<th>Sản phẩm</th>
								<th>Số lượng</th>
								<th>Tên sản phẩm</th>

								<th>Giá</th>
								<th>Giá tổng</th>
								<th>Quản lý</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i=0;
							$total=0;
							while ($row_fetch_giohang = mysqli_fetch_array($sql_lay_giohang)){
								$subtotal = $row_fetch_giohang['soluong']*$row_fetch_giohang['giasanpham'];
								$total += $subtotal;
								$i++;
						?>
							<tr class="rem1">
								<td class="invert"><?php echo $i ?></td>
								<td class="invert-image">
									<a href="single.html">
										<img src="images/<?php echo $row_fetch_giohang['hinhanh'] ?>.jpg" alt=" " height="80" class="img-responsive">
									</a>
								</td>
								<td class="invert">
									<input type="number" min="1" name="soluong[]" value="<?php echo $row_fetch_giohang['soluong'] ?>">
									<input type="hidden" name="product_id[]" value="<?php echo $row_fetch_giohang['sanpham_id'] ?>">
								</td>
								<td class="invert"><?php echo $row_fetch_giohang['tensanpham'] ?></td>
								<td class="invert"><?php echo number_format($row_fetch_giohang['giasanpham']).' vnđ' ?></td>
								<td class="invert"><?php echo number_format($subtotal).' vnđ' ?></td>
								<td class="invert">
									<div class="rem">
										<a href="?quanly=giohang&xoa=<?php echo $row_fetch_giohang['giohang_id'] ?>">Xóa</a>
									</div>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="7">Tổng tiền: <?php echo number_format($total).' vnđ' ?></td>
							</tr>
							<tr>
								<td colspan="7"><input type="submit" class="btn btn-success" value="Cập nhật giỏ hàng" name="capnhatsoluong"></td>
							</tr>
						</tbody>
					</table>
				</form>
				</div>
			</div>
			<div class="checkout-left">
				<div class="address_form_agile mt-sm-5 mt-4">
					<h4 class="mb-sm-4 mb-3">Thêm địa chỉ giao hàng</h4>
					<form action="payment.html" method="post" class="creditly-card-form agileinfo_form">
						<div class="creditly-wrapper wthree, w3_agileits_wrapper">
							<div class="information-wrapper">
								<div class="first-row">
									<div class="controls form-group">
										<input class="billing-address-name form-control" type="text" name="name" placeholder="Điền họ tên" required="">
									</div>
									<div class="w3_agileits_card_number_grids">
										<div class="w3_agileits_card_number_grid_left form-group">
											<div class="controls">
												<input type="text" class="form-control" placeholder="Số điện thoại" name="phone" required="">
											</div>
										</div>
										<div class="w3_agileits_card_number_grid_right form-group">
											<div class="controls">
												<input type="text" class="form-control" placeholder="Địa chỉ" name="address" required="">
											</div>
										</div>
									</div>
									<div class="controls form-group">
										<input type="text" class="form-control" placeholder="email" name="email" required="">
									</div>
									<div class="controls form-group">
										<textarea style="resize: none;" type="text" class="form-control" placeholder="Ghi chú" name="email" required=""></textarea>
									</div>
									<div class="controls form-group">
										<select class="option-w3ls" name="giaohang">
											<option>Chọn hình thức giao hàng</option>
											<option value="1">Thanh toán ATM</option>
											<option value="0">Nhận tiền tại nhà</option>

										</select>
									</div>
								</div>
								<input style="width:20%;" value="Thanh toán" type="submit" name="thanhtoan" class="btn btn-success">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- //checkout page -->