<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function __construct() {
      parent::__construct();
		  $this->login = $this->session->userdata('logged_in_admin');
		  if(empty($this->login)){
			  redirect('login','refresh');
		  }
      $this->load->model("Settings_Model");
		  $this->load->helper("common");
      /* ========FOR WEBSITE SETTING=== */
		  $this->fld_wid="web_id";
		  $this->table_website="tbl_website_info";
		  /* ========FOR VENDOR SETTING== */
		  $this->fld_vnd_id="vnd_id";
		  $this->table_vendor="tbl_vendor";
		  /* ========FOR CATEGORY SETTING===== */
		  $this->fld_cate_id="cate_id";
		  $this->table_category="tbl_category";
      /* ========FOR SUB CATEGORY SETTING== */
		  $this->fld_scate_id="scate_id";
	    $this->table_sub_category="tbl_sub_category";
      /* ====FOR CHILD CATEGORY SETTING=== */
		  $this->fld_child_id="child_id";
	    $this->table_child_category="tbl_child_category";
      /* ========FOR BRAND SETTING===== */
		  $this->fld_brd_id="brd_id";
	    $this->table_brand="tbl_brand";
      /* ========FOR COUNTRY SETTING===== */
		  $this->fld_cnt_id="id";
	    $this->table_country="tbl_country";
      /* ========FOR STATE SETTING===== */
		  $this->fld_st_id="id";
	    $this->table_state="tbl_state";
      /* ========FOR CITY SETTING===== */
		  $this->fld_ct_id="id";
	    $this->table_city="tbl_city";
        /* ========FOR ZIPCODE SETTING===== */
		  $this->fld_zc_id="zc_id";
	    $this->table_zipcode="tbl_zipcode";
      /* ========FOR CURRENCY SETTING===== */
			$this->fld_cr_id="id";
	    $this->table_currency="tbl_currency";
			/* ========FOR COMMISSION SETTING===== */
			$this->fld_cms_id="id";
	    $this->table_commission="tbl_commission";
			/* ========FOR TAX SETTING===== */
			$this->fld_txt_id="txt_id";
	    $this->table_tax="tbl_tax";
			/* ========FOR OPTION SETTING===== */
			$this->fld_opt_id="opt_id";
	    $this->table_option="tbl_option";
	    /* ========FOR TEMPLATE SETTING===== */
			$this->fld_tp_id="tp_id";
		  $this->table_template="tbl_template";
	    /* ========FOR UNIT SETTING===== */
			$this->fld_ut_id="ut_id";
	    $this->table_unit="tbl_unit";
	    /* ========FOR CANCELLATION REASON===== */
	    $this->fld_crid="cr_id";
	    $this->fld_crname="cr_name";
	    $this->cancellation="tbl_cancellation_reason";
    }
    /*=========================== START PINCODE SETTING LIST======================================= */
    function index()
    {
			$content['get_pincode_list']=$this->Settings_Model->get_list($this->fld_piid,$this->table_picode);
			$content['subview']="settings/pincode-setting/pincode_list";
			$this->load->view('layout', $content);
		}

		/*--- START CANCELLATION REASON ---*/
		public function reasons()
		{
			$content['admin']=admin_profile($this->login->mst_email);
			$content['get_reason']=$this->Settings_Model->get_list($this->fld_crid,$this->cancellation);
			$content['subview']="settings/cancellation-reason/reason_list";
			$this->load->view('layout', $content);
		}

	public function addReason()
	{
		$content['admin']=admin_profile($this->login->mst_email);
		/*-- POST REASON --*/
		$REQUESTMETHOD=$this->input->server('REQUEST_METHOD');
		if($REQUESTMETHOD=="POST"){

		   $reasonname=$this->Settings_Model->check_exist($this->fld_crname,$this->input->post('cr_name'),$this->cancellation);
		   //print_r($reasonname);die;
		   if(empty($reasonname)){
				$createdDate = date('Y-m-d H:i:s');
				$data = array(
					'cr_name' => $this->input->post('cr_name'),
					'cr_status' => $this->input->post('cr_status'),
					'cr_created' => date('Y-m-d H:i:s')
				);
			    $result = $this->Settings_Model->save($data,$this->cancellation);
				if($result){
					$this->session->set_flashdata('msg',array('message' => 'Reason has been successfully added.','class' => 'success','type'=>'Ok!'));
			        redirect('settings/addReason');
				}else{
					$this->session->set_flashdata('msg',array('message' => 'Unable to Added.Some error occurred.','class' => 'danger','type'=>'Oops!'));
			        redirect('settings/addReason');
				}
		    }else{
			   $this->session->set_flashdata('msg',array('message' => 'Reason already exist.','class' => 'danger','type'=>'Oops!'));
			   redirect('settings/addReason');
		    }
		}
		/*-- END OF THE POST REASON --*/

		$content['subview']="settings/cancellation-reason/reason_add";
		$this->load->view('layout', $content);
	}

 	public function editReason($crid=null)
 	{
 		$reasonid = decode($crid);
 		if(isset($reasonid)){
 			$content['admin']=admin_profile($this->login->mst_email);
 			/*--- Edit Reason ---*/
 			$content['reason_info']=$this->Settings_Model->get_selected_record($this->fld_crid,$reasonid,$this->cancellation);
			$REQUESTMETHOD=$this->input->server('REQUEST_METHOD');
			if($REQUESTMETHOD== "POST"){
				$data = array(
					'cr_name' => $this->input->post('cr_name'),
					'cr_status' => $this->input->post('cr_status')
				);
				$result = $this->Settings_Model->update($this->fld_crid,$reasonid,$data,$this->cancellation);
				if($result){
					$this->session->set_flashdata('msg',array('message' => 'Reason has been successfully Update.','class' => 'success','type'=>'Ok!'));
					redirect('settings/editReason/'.$crid);
				}else{
					$this->session->set_flashdata('msg',array('message' => 'Unable to Added.Some error occurred.','class' => 'danger','type'=>'Oops!'));
					redirect('settings/editReason/'.$crid);
				}
			}
 			/*--- End of the Edit Reason ---*/
 			$content['subview']="settings/cancellation-reason/reason_edit";
			$this->load->view('layout', $content);
 		}else{
 			redirect('settings/badrequest');
 		}
 	}

 	public function removeReason($crid=null)
 	{
 		$reasonid = decode($crid);
 		if(isset($reasonid)){
 			$result= $this->Settings_Model->delete_single($this->fld_crid,$reasonid,$this->cancellation);
			if($result){
				$this->session->set_flashdata('msg',array('message' => 'Reason has been successfully Delete','class' => 'success','type'=>'Ok!'));
				redirect('settings/reasons');
			}else{
				$this->session->set_flashdata('msg',array('message' => 'Unable to Delete.Some error occurred.','class' => 'danger','type'=>'Oops!'));
				redirect('settings/reasons');
			}
 		}else{
 			redirect('settings/badrequest');
 		}
 	}

 	public function badrequest()
 	{
 		$content['admin']=admin_profile($this->login->mst_email);
 		$content['subview']="settings/cancellation-reason/badrequest";
		$this->load->view('layout', $content);
 	}
    /*=========================== START ADD PINCODE ======================================= */
	public function add_pincode()
	{
		$REQUESTMETHOD=$this->input->server('REQUEST_METHOD');
		if($REQUESTMETHOD== "POST"){
		   $pincode=$this->Settings_Model->check_exist($this->fld_picode,$this->input->post('pincode'),$this->table_picode);

		   if($pincode){
				$createdDate = date('Y-m-d H:i:s');
				$data = array(
					'pin_pincode' => $this->input->post('pincode'),
					'pin_status' => $this->input->post('status'),
					'pin_timestamps' => strtotime($createdDate),
					'pin_created' => date('Y-m-d H:i:s')
				);
			    $result = $this->Settings_Model->save($data,$this->table_picode);
				if($result){
					$this->session->set_flashdata('msg',array('message' => 'Pincode has been successfully added.','class' => 'success','type'=>'Ok!'));
			        redirect('setting/add_pincode');
				}else{
					$this->session->set_flashdata('msg',array('message' => 'Unable to Added.Some error occurred.','class' => 'danger','type'=>'Oops!'));
			        redirect('setting/add_pincode');
				}
		    }else{
			   $this->session->set_flashdata('msg',array('message' => 'Pincode already exist.','class' => 'danger','type'=>'Oops!'));
			   redirect('setting/add_pincode');
		    }
		}
		$content['subview']="setting/pincode-setting/add_pincode";
		$this->load->view('layout', $content);
	}

	/*===========================START EDIT PINCODE  ======================================= */
	public function edit_pincode($id=null)
	{

		$content['pincode_info']=$this->Settings_Model->get_selected_record($this->fld_piid,$id,$this->table_picode);

		$REQUESTMETHOD=$this->input->server('REQUEST_METHOD');
		if($REQUESTMETHOD== "POST"){

			$data = array(
				'pin_pincode' => $this->input->post('pincode'),
				'pin_status' => $this->input->post('status'),
				'pin_updated' => date('Y-m-d H:i:s')
			);

			$result = $this->Settings_Model->update($this->fld_piid,$id,$data,$this->table_picode);
			if($result){
				$this->session->set_flashdata('msg',array('message' => 'Pincode has been successfully Update.','class' => 'success','type'=>'Ok!'));
				redirect('setting');
			}else{
				$this->session->set_flashdata('msg',array('message' => 'Unable to Added.Some error occurred.','class' => 'danger','type'=>'Oops!'));
				redirect('setting');
			}

		}
		$content['subview']="setting/pincode-setting/edit_pincode";
		$this->load->view('layout', $content);
	}

	/*===========================START DELETE PINCODE  ======================================= */
	function delete_pincode($id=null){
		if($id!==NULL) {
		    $result= $this->Settings_Model->delete_single($this->fld_piid,$id,$this->table_picode);
			if($result){
				$this->session->set_flashdata('msg',array('message' => 'Pincode has been successfully Delete','class' => 'success','type'=>'Ok!'));
				redirect('setting');
			}else{
				$this->session->set_flashdata('msg',array('message' => 'Unable to Delete.Some error occurred.','class' => 'danger','type'=>'Oops!'));
				redirect('setting');
				}
		} else {
		   $this->session->set_flashdata('msg',array('message' => 'Row cannot delete!','class' => 'danger','type'=>'Oops!'));
		   redirect('setting');
	    }
	}


	/*===========================START WEBSITE SETTING  ======================================= */

	function website(){
		$content['admin']=admin_profile($this->login->mst_email);
       $content['timzone'] = $this->Settings_Model->get_location_list('zone_name','tbl_timezone');
	   $content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
       $content['currency'] = $this->Settings_Model->location_list('name',$this->table_currency);
       $content['zipcode'] = $this->Settings_Model->location_list('zc_zipcode',$this->table_zipcode);

	   $RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){

			if(isset($_POST['web_company_logo'])&& isset($_POST['web_favicon_icon'])){
			   /* =======For Logo Uplode========= */
				if($_FILES['web_company_logo']['error']>0) {

				} else{
				   $path=FCPATH . 'uploads/website/logo';
				   $image_name='web_company_logo';
				   $img_files= $path.'/'.$this->website->web_company_logo;
				   if (!unlink($img_files)) {} else { }
				   $logo=$this->Settings_Model->images_upload($image_name,$path);
				   $_POST['web_company_logo']= $logo;
				}
			   /* =======For Favicon Uplode========= */
			   if($_FILES['web_favicon_icon']['error']>0) {

				} else{
				   $path=FCPATH . 'uploads/website/favicon';
				   $image_name='web_favicon_icon';
				   $img_files= $path.'/'.$this->website->web_favicon_icon;
				   if (!unlink($img_files)) {} else { }
				   $favicon=$this->Settings_Model->images_upload($image_name,$path);
				   $_POST['web_favicon_icon']= $favicon;
				}
			}

		   $data=$_POST;
		   $result = $this->Settings_Model->update('web_id',$this->website->web_id,$data,$this->table_website);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Website Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/website');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change website setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/website');
			}
		}
		$content['subview']="settings/website-setting/website_setting";
		$this->load->view('layout', $content);;
	}

	function mailsetting(){
		$type='VND';
		$content['MailVND'] = $this->Settings_Model->get($type,$this->table_mail);
		$type='PROMO';
		$content['MailPROMO'] = $this->Settings_Model->get($type,$this->table_mail);
		$type='Customer';
		$content['MailCustomer'] = $this->Settings_Model->get($type,$this->table_mail);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

		if($RequestMethod == "POST"){
			$data=$_POST;
			// $result = $this->Settings_Model->update('web_id',$this->website->web_id,$data,$this->table_website);
			$result = $this->Settings_Model->save($data,$this->table_mail);
			if($result){
			$this->session->set_flashdata('msg',array('message' => 'Mail Data has been successfully Update.','class' => 'success','type'=>'Ok!'));
			redirect('setting/mailsetting');
			}else{
			$this->session->set_flashdata('msg',array('message' => "Unable to Change Mail setting. Some error occurred.",'class' => 'danger','type'=>'Oops!'));
			redirect('setting/mailsetting');
			}
		}
		$content['subview']="setting/mail-setting/MailSetting";
		$this->load->view('layout', $content);;
	}

	/*===========================START DELETE ======================================= */
	function delete($id=null){
		if($id!==NULL) {
			$response= $this->Settings_Model->delete($this->fld_mid,$id,$this->table_mail);

			if($response){
				$this->session->set_flashdata('msg',array('message' => 'Mail Setting has been successfully Delete','class' => 'success','type'=>'Ok!'));
			}else{
				$this->session->set_flashdata('msg',array('message' => 'Unable to Delete. Some error occurred.','class' => 'danger','type'=>'Oops!'));
			}
		}else{
		   $this->session->set_flashdata('msg',array('message' => 'Row cannot delete!','class' => 'danger','type'=>'Oops!'));
	    }
		redirect('setting/mailsetting');
	}

	/*===========================START CHANGE STATUS  ======================================= */
	function change_status($id=null){

		 if($id!==NULL) {

			$get_details=$this->Settings_Model->get_selected_record($this->fld_mid,$id,$this->table_mail);

			if($get_details->mailStatus == '0') $status='1';
			else $status = '0';

			$data = array('mailStatus' => $status,'mailUpdated' => date('Y-m-d H:i:s'));
			$result = $this->Settings_Model->update($this->fld_mid,$id,$data,$this->table_mail);
			if($result) $this->session->set_flashdata('msg',array('message' => 'Mail Status has been successfully Change.','class' => 'success','type'=>'Ok!'));
			else $this->session->set_flashdata('msg',array('message' => 'Unable to change status. Some error occurred.','class' => 'danger','type'=>'Oops!'));
		}else $this->session->set_flashdata('msg',array('message' => 'Status not change!.Some error occurred.','class' => 'danger','type'=>'Oops!'));
		redirect('setting/mailsetting');
	}

	public function getMail()
    {
    	$mailId = $this->input->post('mailId');
    	$result['mail_info'] = $this->Settings_Model->get_selected_record($this->fld_mid,$mailId,$this->table_mail);
    	echo json_encode($result);
	}

	public function updatemail()
    {
		$mailId = $this->input->post('mailId');
		$data = array(
			'mailType'		=> $this->input->post('mailType'),
			'mailMessage'		=> $this->input->post('mailMessage'),
			'mailUpdated' 	=> date('Y-m-d H:i:s'),
		);
		$result = $this->Settings_Model->update($this->fld_mid,$mailId,$data,$this->table_mail);
		if($result) $this->session->set_flashdata('msg',array('message' => 'Mail Status has been successfully Change.','class' => 'success','type'=>'Ok!'));
		else $this->session->set_flashdata('msg',array('message' => 'Unable to change status. Some error occurred.','class' => 'danger','type'=>'Oops!'));
		redirect('setting/mailsetting');
	}


   /*===========================START CATEGORY SETTING  ======================================= */
	function category(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['category'] = $this->Settings_Model->get_cate_list('cate_name','cate_remove',$this->table_category);
		 $content['subview']="settings/category/category_list";
		$this->load->view('layout', $content);;
	}

	function category_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
		$check=$this->Settings_Model->check_exist('cate_name',$this->input->post('cate_name'),$this->table_category);
		if(empty($check)){
		 $path=FCPATH . 'uploads/category';
		 $image_name='cate_img';
		 $img=$this->Settings_Model->images_upload($image_name,$path);
		  $data=array('cate_name' =>$this->input->post('cate_name'),
			  'cate_slug' =>slug($this->input->post('cate_name')),
			  'cate_img' =>$img,
			  'cate_status' =>$this->input->post('cate_status'),
			   'cate_created' =>date('Y-m-d H:i:s')
		   );
		   $result = $this->Settings_Model->save($data,$this->table_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Data has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change website setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-add');
			}
		}else{
		$this->session->set_flashdata('msg',array('message' => "Already Category name Used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
		redirect('settings/category-add');
		}
		}
		$content['subview']="settings/category/category_add";
		$this->load->view('layout', $content);;
	}

function category_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $cate_id=decode($this->uri->segment(3));
        $content['cate_info'] = $this->Settings_Model->get_selected_record('cate_id',$cate_id,$this->table_category);

		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
	    	if(!empty($_FILES['cate_img']['name'])){
	     $path=FCPATH . 'uploads/category';
		   $image_name='cate_img';
		   $img_files= $path.'/'.$content['cate_info']->cate_img;
		 if (!unlink($img_files)) {} else { }
		 $img=$this->Settings_Model->images_upload($image_name,$path);
		   $data=array('cate_name' =>$this->input->post('cate_name'),
			  'cate_slug' =>slug($this->input->post('cate_name')),
			  'cate_img' =>$img,
			  'cate_status' =>$this->input->post('cate_status'),
			   'cate_updated' =>date('Y-m-d H:i:s')
		   );
		  }else{ $data=array('cate_name' =>$this->input->post('cate_name'),
			  'cate_slug' =>slug($this->input->post('cate_name')),
			  'cate_status' =>$this->input->post('cate_status'),
			   'cate_updated' =>date('Y-m-d H:i:s')
		   );		}
		   $result = $this->Settings_Model->update('cate_id',$cate_id,$data,$this->table_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/category/category_edit";
		$this->load->view('layout', $content);;
	}
	function category_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $cate_id=$this->uri->segment(3);
        $cate_status=$this->uri->segment(4);
		$data=array('cate_status'=>$cate_status,
		'cate_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('cate_id',$cate_id,$data,$this->table_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category');
			}
		}
function category_delete(){
        $cate_id=$this->uri->segment(3);
		$data=array('cate_remove'=>'1',
		'cate_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('cate_id',$cate_id,$data,$this->table_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Data has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category');
			}

	}

	/*===========================START SUB CATEGORY SETTING  ======================================= */
	function sub_category(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['sub_category'] = $this->Settings_Model->get_cate_list('scate_name','scate_remove',$this->table_sub_category);
		 $content['subview']="settings/sub_category/sub_category_list";
		$this->load->view('layout', $content);;
	}

	function sub_category_add(){
		$content['admin']=admin_profile($this->login->mst_email);
       $content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
		$check=$this->Settings_Model->check_double_exist('scate_name',$this->input->post('scate_name'),'cate_id',$this->input->post('cate_id'),$this->table_sub_category);
		if(empty($check)){
		 $_POST['scate_slug']= slug($this->input->post('scate_name'));
		 $_POST['scate_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_sub_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Sub Category Data has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/sub-category-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Sub Category .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/sub-category-add');
			}
		}else{
		$this->session->set_flashdata('msg',array('message' => "Already Sub Category name Used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
		redirect('settings/sub-category-add');
		}
		}
		$content['subview']="settings/sub_category/sub_category_add";
		$this->load->view('layout', $content);;
	}

function sub_category_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $scate_id=decode($this->uri->segment(3));
		$content['scate_info'] = $this->Settings_Model->get_selected_record('scate_id',$scate_id,$this->table_sub_category);
         $content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['scate_slug']= slug($this->input->post('scate_name'));
		 $_POST['scate_updated']= date('Y-m-d H:i:s');

		   $data=$_POST;
		   $result = $this->Settings_Model->update('scate_id',$scate_id,$data,$this->table_sub_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Sub Category Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/sub-category-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Sub Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/sub-category-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/sub_category/sub_category_edit";
		$this->load->view('layout', $content);;
	}
	function sub_category_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $scate_id=$this->uri->segment(3);
        $scate_status=$this->uri->segment(4);
		$data=array('scate_status'=>$scate_status,
		'scate_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('scate_id',$scate_id,$data,$this->table_sub_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Sub Category Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/sub-category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Sub Category Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/sub-category');
			}
		}
function sub_category_delete(){
        $scate_id=$this->uri->segment(3);
		$data=array('scate_remove'=>'1',
		'scate_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('scate_id',$scate_id,$data,$this->table_sub_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Sub Category Data has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/sub-category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Sub Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/sub-category');
			}

	}

	/*===========================START CHILD CATEGORY SETTING  ======================================= */
	function child_category(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['child_category'] = $this->Settings_Model->get_cate_list('child_name','child_remove',$this->table_child_category);
		 $content['subview']="settings/child_category/child_category_list";
		$this->load->view('layout', $content);;
	}

	function child_category_add(){
		$content['admin']=admin_profile($this->login->mst_email);
       $content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
        $content['sub_category'] = $this->Settings_Model->cate_list('scate_name','scate_remove','scate_status',$this->table_sub_category);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
		$check=$this->Settings_Model->check_double_exist('child_name',$this->input->post('child_name'),
		'scate_id',$this->input->post('scate_id'),$this->table_child_category);
		if(empty($check)){
		 $_POST['child_slug']= slug($this->input->post('child_name'));
		  $_POST['cate_id']= cate_id($this->input->post('scate_id'));
		 $_POST['child_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_child_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Child Category Data has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/child-category-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Child Category .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/child-category-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Child Category Used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/child-category-add');
		}
		}
		$content['subview']="settings/child_category/child_category_add";
		$this->load->view('layout', $content);;
	}

function child_category_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $child_id=decode($this->uri->segment(3));
		$content['child_info'] = $this->Settings_Model->get_selected_record('child_id',$child_id,$this->table_child_category);
        $content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
        $content['sub_category'] = $this->Settings_Model->cate_list('scate_name','scate_remove','scate_status',$this->table_sub_category);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['child_slug']= slug($this->input->post('child_name'));
		  $_POST['cate_id']= cate_id($this->input->post('scate_id'));
		 $_POST['child_updated']= date('Y-m-d H:i:s');

		   $data=$_POST;
		   $result = $this->Settings_Model->update('child_id',$child_id,$data,$this->table_child_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Child Category Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/child-category-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Child Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/child-category-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/child_category/child_category_edit";
		$this->load->view('layout', $content);;
	}
function child_category_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $child_id=$this->uri->segment(3);
        $child_status=$this->uri->segment(4);

		$data=array('child_status'=>$child_status,
		'child_updated'=> date('Y-m-d H:i:s'));

		   $result = $this->Settings_Model->update('child_id',$child_id,$data,$this->table_child_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Child Category Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/child-category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Child Category Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/child-category');
			}
		}
function child_category_delete(){
        $child_id=$this->uri->segment(3);
		$data=array('child_remove'=>'1',
		'child_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('child_id',$child_id,$data,$this->table_child_category);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Child Category Data has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/child-category');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Child Category setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/child-category');
			}

	}

	/*====START BRAND SETTING========== */
	function brand(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['brand'] = $this->Settings_Model->get_cate_list('brd_name','brd_remove',$this->table_brand);
		 $content['subview']="settings/brand/brand_list";
		$this->load->view('layout', $content);;
	}

	function brand_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
		$check=$this->Settings_Model->check_exist('brd_name',$this->input->post('brd_name'),$this->table_brand);
		if(empty($check)){
		 $path=FCPATH . 'uploads/brand';
		 $image_name='brd_img';
		 $img=$this->Settings_Model->images_upload($image_name,$path);
		  $data=array('brd_name' =>$this->input->post('brd_name'),
			  'brd_slug' =>slug($this->input->post('brd_name')),
			  'brd_img' =>$img,
			  'brd_status' =>$this->input->post('brd_status'),
			   'brd_created' =>date('Y-m-d H:i:s')
		   );
		   $result = $this->Settings_Model->save($data,$this->table_brand);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Brand Data has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/brand-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Brand .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/brand-add');
			}
		}else{
			$this->session->set_flashdata('msg',array('message' => "Already Brand Name Used !.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/brand-add');
		}
		}
		$content['subview']="settings/brand/brand_add";
		$this->load->view('layout', $content);;
	}

function brand_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $brd_id=decode($this->uri->segment(3));
		$content['brand_info'] = $this->Settings_Model->get_selected_record('brd_id',$brd_id,$this->table_brand);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
	    if(!empty($_FILES['brd_img']['name'])){
	     $path=FCPATH . 'uploads/brand';
		   $image_name='brd_img';
		   $img_files= $path.'/'.$content['brand_info']->brd_img;
		 if (!unlink($img_files)) {} else { }
		 $img=$this->Settings_Model->images_upload($image_name,$path);
		 $data=array('brd_name' =>$this->input->post('brd_name'),
			  'brd_slug' =>slug($this->input->post('brd_name')),
			  'brd_img' =>$img,
			  'brd_status' =>$this->input->post('brd_status'),
			   'brd_updated' =>date('Y-m-d H:i:s')
		   );
		  }else{ $data=array('brd_name' =>$this->input->post('brd_name'),
			  'brd_slug' =>slug($this->input->post('brd_name')),
			  'brd_status' =>$this->input->post('brd_status'),
			   'brd_updated' =>date('Y-m-d H:i:s')
		   );
		  	}


		   $result = $this->Settings_Model->update('brd_id',$brd_id,$data,$this->table_brand);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Brand Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/brand-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Brand setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/brand-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/brand/brand_edit";
		$this->load->view('layout', $content);;
	}

function brand_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $brd_id=$this->uri->segment(3);
        $brd_status=$this->uri->segment(4);
		$data=array('brd_status'=>$brd_status,
		'brd_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('brd_id',$brd_id,$data,$this->table_brand);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Brand Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/brand');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Brand Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/brand');
			}
		}

function brand_delete(){
        $brd_id=$this->uri->segment(3);
		$data=array('brd_remove'=>'1',
		'brd_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('brd_id',$brd_id,$data,$this->table_brand);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Brand Data has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/brand');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Brand setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/brand');
			}

	}


	/*====START OPTION SETTING========== */
	function option(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['option'] = $this->Settings_Model->getlist('opt_name',$this->table_option);
		 $content['subview']="settings/option/option_list";
		$this->load->view('layout', $content);;
	}

	function option_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
		$check=$this->Settings_Model->check_exist('opt_name',$this->input->post('opt_name'),$this->table_option);
		if(empty($check)){
		 $_POST['opt_slug']= slug($this->input->post('opt_name'));
		 $_POST['opt_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_option);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Option Data has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/option-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Option .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/option-add');
			}
		}else{
			$this->session->set_flashdata('msg',array('message' => "Already Option Name Used !.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/option-add');
		}
		}
		$content['subview']="settings/option/option_add";
		$this->load->view('layout', $content);;
	}

function option_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $opt_id=decode($this->uri->segment(3));
		$content['option_info'] = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['opt_slug']= slug($this->input->post('opt_name'));
		 $_POST['opt_updated']= date('Y-m-d H:i:s');

		   $data=$_POST;
		   $result = $this->Settings_Model->update('opt_id',$opt_id,$data,$this->table_option);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Option Data has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/option-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Option setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/option-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/option/option_edit";
		$this->load->view('layout', $content);;
	}

function option_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $opt_id=$this->uri->segment(3);
        $opt_status=$this->uri->segment(4);
		$data=array('opt_status'=>$opt_status,
		'opt_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('opt_id',$opt_id,$data,$this->table_option);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Option Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/option');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Option Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/option');
			}
		}

public function option_value_add()
    {
    	$opt_id = $this->input->post('opt_id');
		$result['option'] = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
		if(!empty($result['option']->opt_value)){
		$option_value=explode(', ',$result['option']->opt_value);
		$arr = []; //create empty array
		$i=1;
		foreach($option_value as $list) {
			$url=$opt_id.",'".$list."','".base_url()."'";
		    $arr[] = '<tr role="row" >
					   <td class="sorting_1" >'.$i.'</td>
					   <td>'.$list.'</td>
					   <td align="center">
					   <a href="javascript:void(0);" onclick="javascript:OptionvalueDelete('.$url.')" class="btn btn-danger btn-sm mb-2 mb-xl-0 text-white" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>

					  </td>
                      </tr>';$i++;
          	}

         $result['option_value'] =$arr;
     }else{  $result['option_value'] ='';}

    	echo json_encode($result);
    }

    public function option_value_save()
    {
    	$opt_id = $this->input->post('opt_id');
    	$get_option = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
    	$opt_value = $this->input->post('opt_value');
    	$z=$get_option->opt_value.', '.$opt_value;
    	if(!empty($get_option->opt_value)){
    	$data= array('opt_value' =>$z);
        }else{$data= array('opt_value' =>$opt_value);}
    	$save = $this->Settings_Model->update('opt_id',$opt_id,$data,$this->table_option);
		$result['option'] = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
		if(!empty($result['option']->opt_value)){
		$option_value=explode(', ',$result['option']->opt_value);
		$arr = []; //create empty array
		$i=1;
		foreach($option_value as $list) {
			$url=$opt_id.",'".$list."','".base_url()."'";
		    $arr[] = '<tr role="row" >
					   <td class="sorting_1" >'.$i.'</td>
					   <td>'.$list.'</td>
					   <td align="center">
					   <a href="javascript:void(0);" onclick="javascript:OptionvalueDelete('.$url.')" class="btn btn-danger btn-sm mb-2 mb-xl-0 text-white" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>

					  </td>
                      </tr>';$i++;

          	}

         $result['option_value'] =$arr;
     }else{  $result['option_value'] ='';}

    	echo json_encode($result);
    }


	public function option_value_delete()
    {
    	$opt_id = $this->input->post('opt_id');
    	$get_option = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
    	$opt_value = ", ".$this->input->post('opt_value');
    	$z=str_replace($opt_value,"",$get_option->opt_value);
    	if($get_option->opt_value==$z) {
	    	$opt_value = $this->input->post('opt_value').", ";
	    	$k=str_replace($opt_value,"",$get_option->opt_value);

	    	if($get_option->opt_value==$k) {
		    	$opt_value = $this->input->post('opt_value');
		    	$kk=str_replace($opt_value,"",$get_option->opt_value);
		    	$data= array('opt_value' =>$kk);
	    	}else{
	    		$data= array('opt_value' =>$k);
	        }
    	}else{
    	 $data= array('opt_value' =>$z);
        }
    	$save = $this->Settings_Model->update('opt_id',$opt_id,$data,$this->table_option);
		$result['option'] = $this->Settings_Model->get_selected_record('opt_id',$opt_id,$this->table_option);
		if(!empty($result['option']->opt_value)){
		$option_value=explode(', ',$result['option']->opt_value);
		$arr = []; //create empty array
		$i=1;
		foreach($option_value as $list) {
			$url=$opt_id.",'".$list."','".base_url()."'";
		    $arr[] = '<tr role="row" >
					   <td class="sorting_1" >'.$i.'</td>
					   <td>'.$list.'</td>
					   <td align="center">
					   <a href="javascript:void(0);" onclick="javascript:OptionvalueDelete('.$url.')" class="btn btn-danger btn-sm mb-2 mb-xl-0 text-white" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>

					  </td>
                      </tr>';$i++;

          	}
         $result['option_value'] =$arr;
     }else{  $result['option_value'] ='';}

    	echo json_encode($result);
    }

	/*====START COUNTRY SETTING========== */
	function country(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['country'] = $this->Settings_Model->get_location_list('name',$this->table_country);
		 $content['subview']="settings/country/country_list";
		$this->load->view('layout', $content);
	}

	function country_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');

	    if($RequestMethod == "POST"){
        $check=$this->Settings_Model->check_exist('cnt_name',$this->input->post('cnt_name'),$this->table_country);
		if(empty($check)){
		 $_POST['cnt_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_country);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Country has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/country-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Country .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/country-add');
			}
		}else{
			$this->session->set_flashdata('msg',array('message' => "Already Country Name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/country-add');}
		}
		$content['subview']="settings/country/country_add";
		$this->load->view('layout', $content);;
	}

function country_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $cnt_id=decode($this->uri->segment(3));
		$content['country_info'] = $this->Settings_Model->get_selected_record('cnt_id',$cnt_id,$this->table_country);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['cnt_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('cnt_id',$cnt_id,$data,$this->table_country);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Country has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/country-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Country setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/country-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/country/country_edit";
		$this->load->view('layout', $content);;
	}
function country_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $cnt_id=$this->uri->segment(3);
        $cnt_status=$this->uri->segment(4);
		$data=array('cnt_status'=>$cnt_status,
		'cnt_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('cnt_id',$cnt_id,$data,$this->table_country);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Country Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/country');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Country Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/country');
			}
		}

function country_delete(){
        $cnt_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('cnt_id',$cnt_id,$this->table_country);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Country has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/country');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Country setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/country');
			}

	}

	/*====START STATE SETTING========== */
	function state(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['state'] = $this->Settings_Model->get_location_list('name',$this->table_state);
		 $content['subview']="settings/state/state_list";
		$this->load->view('layout', $content);;
	}

	function state_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
	    if($RequestMethod == "POST"){
        $check=$this->Settings_Model->check_exist('name',$this->input->post('st_name'),$this->table_state);
		if(empty($check)){
		 $_POST['st_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_state);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'State has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/state-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change State .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/state-add');
			}
		}else{
		$this->session->set_flashdata('msg',array('message' => "Already State Name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
		redirect('settings/state-add');
		}
		}
		$content['subview']="settings/state/state_add";
		$this->load->view('layout', $content);;
	}

function state_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $st_id=decode($this->uri->segment(3));
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
		$content['state_info'] = $this->Settings_Model->get_selected_record('st_id',$st_id,$this->table_state);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['st_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('st_id',$st_id,$data,$this->table_state);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'State has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/state-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change State setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/state-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/state/state_edit";
		$this->load->view('layout', $content);;
	}
	function state_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $st_id=$this->uri->segment(3);
        $st_status=$this->uri->segment(4);
		$data=array('st_status'=>$st_status,
		'st_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('st_id',$st_id,$data,$this->table_state);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'State Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/state');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change State Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/state');
			}
		}
function state_delete(){
        $st_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('st_id',$st_id,$this->table_state);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'State has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/state');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change State setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/state');
			}

	}
	/*====START CITY SETTING========== */
function city(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['city'] = $this->Settings_Model->get_location_list('name',$this->table_city);
		 $content['subview']="settings/city/city_list";
		$this->load->view('layout', $content);;
	}

function city_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
	    if($RequestMethod == "POST"){
 $check=$this->Settings_Model->check_exist('ct_name',$this->input->post('ct_name'),$this->table_city);
		if(empty($check)){
		 $_POST['ct_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_city);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'City has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/city-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change City .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/city-add');
			}
		}else{
			$this->session->set_flashdata('msg',array('message' => "Already City Name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/city-add');
			   }
		}
		$content['subview']="settings/city/city_add";
		$this->load->view('layout', $content);;
	}
	function city_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $ct_id=$this->uri->segment(3);
        $ct_status=$this->uri->segment(4);
		$data=array('ct_status'=>$ct_status,
		'ct_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('ct_id',$ct_id,$data,$this->table_city);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'City Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/city');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change City Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/city');
			}
		}

function city_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $ct_id=decode($this->uri->segment(3));
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
		$content['state'] = $this->Settings_Model->location_list('name',$this->table_state);
		$content['city_info'] = $this->Settings_Model->get_selected_record('state_id',$ct_id,$this->table_city);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['ct_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('ct_id',$ct_id,$data,$this->table_city);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'City has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/city-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change City setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/city-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/city/city_edit";
		$this->load->view('layout', $content);;
	}

function city_delete(){
           $ct_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('ct_id',$ct_id,$this->table_city);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'City has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/city');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change City setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/city');
			}

	}

	public function getState()
	{
		$CID = $this->input->get('CID');
		$getdata['State'] = $this->Settings_Model->get_location('st_name','st_status',$this->fld_cnt_id,$CID,$this->table_state);
		echo json_encode($getdata['State']);
	}

	public function getCity()
	{
		$SID = $this->input->get('SID');
		$getdata['City'] = $this->Settings_Model->get_location('ct_name','ct_status',$this->fld_st_id,$SID,$this->table_city);
		echo json_encode($getdata['City']);
	}

	public function getZip()
	{
		$CID = $this->input->get('CID');
		$getdata['Zip'] = $this->Settings_Model->get_location('zc_zipcode','zc_status',$this->fld_ct_id,$CID,$this->table_zipcode);
		echo json_encode($getdata['Zip']);
	}


		/*====START ZIPCODE SETTING========== */
function zipcode(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['pincode'] = $this->Settings_Model->get_location_list('zc_zipcode',$this->table_zipcode);
		 $content['subview']="settings/pincode-setting/pincode_list";
		$this->load->view('layout', $content);;
	}



function zip_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('zc_zipcode',$this->input->post('zc_zipcode'),$this->table_zipcode);
		if(empty($check)){
		 $_POST['zc_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_zipcode);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Zip Code has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/zip-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Zip Code .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/zip-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Zipcode used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/zip-add');
		}
		}
		$content['subview']="settings/pincode-setting/add_pincode";
		$this->load->view('layout', $content);;
	}

function zip_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $zc_id=decode($this->uri->segment(3));
		$content['country'] = $this->Settings_Model->location_list('name',$this->table_country);
		$content['state'] = $this->Settings_Model->location_list('name',$this->table_state);
		$content['city'] = $this->Settings_Model->location_list('name',$this->table_city);
		$content['zip_info'] = $this->Settings_Model->get_selected_record('zc_id',$zc_id,$this->table_zipcode);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['zc_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('zc_id',$zc_id,$data,$this->table_zipcode);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Zip Code has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/zip-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Zip Code  setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/zip-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/pincode-setting/edit_pincode";
		$this->load->view('layout', $content);;
	}

	function zip_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $zc_id=$this->uri->segment(3);
        $zc_status=$this->uri->segment(4);
		$data=array('zc_status'=>$zc_status,
		'zc_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('zc_id',$zc_id,$data,$this->table_zipcode);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Zipcode Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/zipcode');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Zipcode Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/zipcode');
			}
		}

function zip_delete(){
           $zc_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('zc_id',$zc_id,$this->table_zipcode);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Zip Code has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/zipcode');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Zip Code .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/zipcode');
			}

	}


	/*====START CURRENCY SETTING========== */
function currency(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['currency'] = $this->Settings_Model->get_location_list('name',$this->table_currency);
		 $content['subview']="settings/currency/currency_list";
		$this->load->view('layout', $content);;
	}

function currency_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['currency'] = $this->Settings_Model->get_location_list('name',$this->table_currency);
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('name',$this->input->post('name'),$this->table_currency);
		if(empty($check)){
		 $_POST['created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_currency);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Currency has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/currency-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Currency  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/currency-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Currency Name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/currency-add');
		}
		}
		$content['subview']="settings/currency/currency_add";
		$this->load->view('layout', $content);;
	}

function currency_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $Id=decode($this->uri->segment(3));
		$content['currency_info'] = $this->Settings_Model->get_selected_record('id',$Id,$this->table_currency);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('id',$Id,$data,$this->table_currency);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Currency has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/currency-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Currency setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/currency-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/currency/currency_edit";
		$this->load->view('layout', $content);;
	}

	function currency_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $id=$this->uri->segment(3);
        $status=$this->uri->segment(4);
		$data=array('status'=>$status,
		'updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update('id',$id,$data,$this->table_currency);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Currency Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/currency');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Currency Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/currency');
			}
		}

     function currency_delete(){
          $id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('id',$id,$this->table_currency);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Currency has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/currency');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Currency .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/currency');
			}

	}


	/*====START COMMISSION SETTING========== */
function commission()
  {
	$content['admin']=admin_profile($this->login->mst_email);
    $content['commission'] = $this->Settings_Model->get_location_list($this->fld_cms_id,$this->table_commission);
    $content['subview']="settings/commission/commission_list";
	$this->load->view('layout', $content);;
	}

function commission_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
        $content['vendor'] = $this->Settings_Model->location_list('vnd_name',$this->table_vendor);
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_double_exist('vnd_id',$this->input->post('vnd_id'),'cate_id',$this->input->post('cate_id'),$this->table_commission);
		if(empty($check)){
		 $_POST['created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_commission);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Commission has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/commission-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Commission  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/commission-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Commission used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/commission-add');
		}
		}
		$content['subview']="settings/commission/commission_add";
		$this->load->view('layout', $content);;
	}

function commission_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $Id=decode($this->uri->segment(3));
		$content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
        $content['vendor'] = $this->Settings_Model->location_list('vnd_name',$this->table_vendor);
		$content['commission_info'] = $this->Settings_Model->get_selected_record('id',$Id,$this->table_commission);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update('id',$Id,$data,$this->table_commission);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Commission has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/commission-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Commission setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/commission-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/commission/commission_edit";
		$this->load->view('layout', $content);;
	}


     function commission_delete(){
          $id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single('id',$id,$this->table_commission);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Commission has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/commission');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Commission .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/commission');
			}

	}


		/*====START CATEGORY TAX SETTING========== */
function category_tax(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['category_tax'] = $this->Settings_Model->get_list($this->fld_txt_id,$this->table_tax);
		 $content['subview']="settings/category_tax/category_tax_list";
		$this->load->view('layout', $content);;
	}

function category_tax_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
		$content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('cate_id',$this->input->post('cate_id'),$this->table_tax);
		if(empty($check)){
		 $_POST['txt_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Tax has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-tax-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category Tax  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-tax-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Category Tax name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-tax-add');
		}
		}
		$content['subview']="settings/category_tax/category_tax_add";
		$this->load->view('layout', $content);;
	}

function category_tax_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $txt_id=decode($this->uri->segment(3));
		$content['tax_info'] = $this->Settings_Model->get_selected_record($this->fld_txt_id,$txt_id,$this->table_tax);
        $content['category'] = $this->Settings_Model->cate_list('cate_name','cate_remove','cate_status',$this->table_category);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['txt_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update($this->fld_txt_id,$txt_id,$data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Tax has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-tax-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category Tax setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-tax-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/category_tax/category_tax_edit";
		$this->load->view('layout', $content);;
	}

	function category_tax_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $txt_id=$this->uri->segment(3);
        $txt_status=$this->uri->segment(4);
		$data=array('txt_status'=>$txt_status,
		'txt_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update($this->fld_txt_id,$txt_id,$data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Tax Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-tax');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category Tax Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-tax');
			}
		}

     function category_tax_delete(){
          $txt_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single($this->fld_txt_id,$txt_id,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Category Tax has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/category-tax');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Category Tax .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/category-tax');
			}

	}

		/*====START TAX SETTING========== */
function tax(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['tax'] = $this->Settings_Model->get_list($this->fld_txt_id,$this->table_tax);
		 $content['subview']="settings/tax/tax_list";
		$this->load->view('layout', $content);;
	}

function tax_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('txt_name',$this->input->post('txt_name'),$this->table_tax);
		if(empty($check)){
		 $_POST['txt_created']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->save($data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Tax has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/tax-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Tax  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/tax-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Tax name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/tax-add');
		}
		}
		$content['subview']="settings/tax/tax_add";
		$this->load->view('layout', $content);;
	}

function tax_edit(){
		$content['admin']=admin_profile($this->login->mst_email);
        $txt_id=decode($this->uri->segment(3));
		$content['tax_info'] = $this->Settings_Model->get_selected_record($this->fld_txt_id,$txt_id,$this->table_tax);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
		 $_POST['txt_updated']= date('Y-m-d H:i:s');
		   $data=$_POST;
		   $result = $this->Settings_Model->update($this->fld_txt_id,$txt_id,$data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Tax has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/tax-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Tax setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/tax-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/tax/tax_edit";
		$this->load->view('layout', $content);;
	}

	function tax_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $txt_id=$this->uri->segment(3);
        $txt_status=$this->uri->segment(4);
		$data=array('txt_status'=>$txt_status,
		'txt_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update($this->fld_txt_id,$txt_id,$data,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Tax Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/tax');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Tax Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/tax');
			}
		}

     function tax_delete(){
          $txt_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single($this->fld_txt_id,$txt_id,$this->table_tax);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Tax has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/tax');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Tax .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/tax');
			}

	}

/*====START TEMPLATES SETTING========== */
function templates(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['template'] = $this->Settings_Model->get_list($this->fld_tp_id,$this->table_template);
		 $content['subview']="settings/templates/template_list";
		$this->load->view('layout', $content);;
	}

function template_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('tp_name',$this->input->post('tp_name'),$this->table_template);
		if(empty($check)){
       $data=array(
		   'tp_name'=>$this->input->post('tp_name'),
		   'tp_subject'=>$this->input->post('tp_subject'),
		   'tp_body'=>$this->input->post('tp_body'),
		   'tp_status'=>'1',
		   'tp_created'=>date('Y-m-d H:i:s')
	   );
		   $result = $this->Settings_Model->save($data,$this->table_template);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Template has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/template-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Template  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/template-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Template name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/template-add');
		}
		}
		$content['subview']="settings/templates/template_add";
		$this->load->view('layout', $content);;
	}

function template_edit(){

		$content['admin']=admin_profile($this->login->mst_email);
        $tp_id=decode($this->uri->segment(3));

		$content['tamplate_info'] = $this->Settings_Model->get_selected_record($this->fld_tp_id,$tp_id,$this->table_template);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $data=array(
		   'tp_name'=>$this->input->post('tp_name'),
		   'tp_subject'=>$this->input->post('tp_subject'),
		   'tp_body'=>$this->input->post('tp_body'),
		   'tp_updated'=>date('Y-m-d H:i:s')
	      );

		   $result = $this->Settings_Model->update($this->fld_tp_id,$tp_id,$data,$this->table_template);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Template has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/template-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Template setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/template-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/templates/template_edit";
		$this->load->view('layout', $content);;
	}

	function template_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $tp_id=$this->uri->segment(3);
        $tp_status=$this->uri->segment(4);
		$data=array('tp_status'=>$tp_status,
		'tp_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update($this->fld_tp_id,$tp_id,$data,$this->table_template);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Template Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/templates');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Template Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/templates');
			}
		}

     function template_delete(){
          $tp_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single($this->fld_tp_id,$tp_id,$this->table_template);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Template has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/templates');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Template .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/templates');
			}

	}



	/*====START Dimensions UNIT SETTING========== */
function dimensions_unit(){
		$content['admin']=admin_profile($this->login->mst_email);
      $content['unit'] = $this->Settings_Model->getlist('ut_dime_name',$this->table_unit);
		 $content['subview']="settings/unit/dimensions-unit";
		$this->load->view('layout', $content);;
	}

function dimensions_unit_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('ut_dime_name',$this->input->post('ut_dime_name'),$this->table_unit);
		if(empty($check)){
       $data=array(
		   'ut_dime_name'=>$this->input->post('ut_dime_name'),
		   'ut_status'=>$this->input->post('ut_status'),
		   'ut_created'=>date('Y-m-d H:i:s')
	    );
		   $result = $this->Settings_Model->save($data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Dimensions Unit has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/dimensions-unit-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Dimensions Unit  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/dimensions-unit-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Dimensions Unit name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/dimensions-unit-add');
		}
		}
		$content['subview']="settings/unit/dimensions-unit-add";
		$this->load->view('layout', $content);;
	}

function dimensions_unit_edit(){

		$content['admin']=admin_profile($this->login->mst_email);
        $ut_id=decode($this->uri->segment(3));

		$content['unit_info'] = $this->Settings_Model->get_selected_record($this->fld_ut_id,$ut_id,$this->table_unit);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $data=array(
		   'ut_dime_name'=>$this->input->post('ut_dime_name'),
		   'ut_status'=>$this->input->post('ut_status'),
		   'ut_updated'=>date('Y-m-d H:i:s')
	      );

		   $result = $this->Settings_Model->update($this->fld_ut_id,$ut_id,$data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Dimensions Unit has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/dimensions-unit-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Dimensions Unit setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/dimensions-unit-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/unit/dimensions-unit-edit";
		$this->load->view('layout', $content);;
	}

	function dimensions_unit_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $ut_id=$this->uri->segment(3);
        $ut_status=$this->uri->segment(4);
		$data=array('ut_status'=>$ut_status,
		'ut_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update($this->fld_ut_id,$ut_id,$data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Dimensions Unit Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/dimensions-unit');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Dimensions Unit Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/dimensions-unit');
			}
		}

     function dimensions_unit_delete(){
          $ut_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single($this->fld_ut_id,$ut_id,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Dimensions Unit has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/dimensions-unit');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Dimensions Unit .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/dimensions-unit');
			}

	}


	/*====START WEIGHT UNIT SETTING========== */
function weight_unit(){
		$content['admin']=admin_profile($this->login->mst_email);
         $content['unit'] = $this->Settings_Model->getlist('ut_weight_name',$this->table_unit);
		 $content['subview']="settings/unit/weight-unit";
		$this->load->view('layout', $content);;
	}

function weight_unit_add(){
		$content['admin']=admin_profile($this->login->mst_email);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $check=$this->Settings_Model->check_exist('ut_weight_name',$this->input->post('ut_weight_name'),$this->table_unit);
		if(empty($check)){
       $data=array(
		   'ut_weight_name'=>$this->input->post('ut_weight_name'),
		   'ut_status'=>$this->input->post('ut_status'),
		   'ut_created'=>date('Y-m-d H:i:s')
	    );
		   $result = $this->Settings_Model->save($data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Weight Unit has been successfully save.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/weight-unit-add');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Weight Unit  .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/weight-unit-add');
			}
		}else{
			 $this->session->set_flashdata('msg',array('message' => "Already Weight Unit name used.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/weight-unit-add');
		}
		}
		$content['subview']="settings/unit/weight-unit-add";
		$this->load->view('layout', $content);;
	}

function weight_unit_edit(){

		$content['admin']=admin_profile($this->login->mst_email);
        $ut_id=decode($this->uri->segment(3));

		$content['unit_info'] = $this->Settings_Model->get_selected_record($this->fld_ut_id,$ut_id,$this->table_unit);
		$RequestMethod = $this->input->server('REQUEST_METHOD');
	    if($RequestMethod == "POST"){
       $data=array(
		   'ut_weight_name'=>$this->input->post('ut_weight_name'),
		   'ut_status'=>$this->input->post('ut_status'),
		   'ut_updated'=>date('Y-m-d H:i:s')
	      );

		   $result = $this->Settings_Model->update($this->fld_ut_id,$ut_id,$data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Weight Unit has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/weight-unit-edit/'.$this->uri->segment(3));
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Weight Unit setting .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/weight-unit-edit/'.$this->uri->segment(3));
			}
		}
		$content['subview']="settings/unit/weight-unit-edit";
		$this->load->view('layout', $content);;
	}

	function weight_unit_status(){
		$content['admin']=admin_profile($this->login->mst_email);
        $ut_id=$this->uri->segment(3);
        $ut_status=$this->uri->segment(4);
		$data=array('ut_status'=>$ut_status,
		'ut_updated'=> date('Y-m-d H:i:s'));
		   $result = $this->Settings_Model->update($this->fld_ut_id,$ut_id,$data,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Weight Unit Status has been successfully Update.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/weight-unit');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Weight Unit Status .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/weight-unit');
			}
		}

     function weight_unit_delete(){
          $ut_id=$this->uri->segment(3);
		   $result = $this->Settings_Model->delete_single($this->fld_ut_id,$ut_id,$this->table_unit);

		   if($result){
			   $this->session->set_flashdata('msg',array('message' => 'Weight Unit has been successfully Delete.','class' => 'success','type'=>'Success!','icon'=>'thumbs-up'));
			   redirect('settings/weight-unit');
			}else{
			   $this->session->set_flashdata('msg',array('message' => "Unable to Change Weight Unit .Some error occurred.",'class' => 'danger','type'=>'Oops!','icon'=>'slash'));
			   redirect('settings/weight-unit');
			}

	}


}
