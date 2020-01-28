<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE concur
class cl_concur { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $h06_refer = 0; 
   var $h06_eaber = null; 
   var $h06_daber_dia = null; 
   var $h06_daber_mes = null; 
   var $h06_daber_ano = null; 
   var $h06_daber = null; 
   var $h06_ehomo = null; 
   var $h06_dhomo_dia = null; 
   var $h06_dhomo_mes = null; 
   var $h06_dhomo_ano = null; 
   var $h06_dhomo = null; 
   var $h06_concur = null; 
   var $h06_dvalid_dia = null; 
   var $h06_dvalid_mes = null; 
   var $h06_dvalid_ano = null; 
   var $h06_dvalid = null; 
   var $h06_dprorr_dia = null; 
   var $h06_dprorr_mes = null; 
   var $h06_dprorr_ano = null; 
   var $h06_dprorr = null; 
   var $h06_dpubl_dia = null; 
   var $h06_dpubl_mes = null; 
   var $h06_dpubl_ano = null; 
   var $h06_dpubl = null; 
   var $h06_nrproc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h06_refer = int4 = Codigo do Concurso 
                 h06_eaber = varchar(10) = Edital de Abertura 
                 h06_daber = date = Data da Abertura 
                 h06_ehomo = varchar(10) = Edital de Homologação 
                 h06_dhomo = date = Data da Homologacao 
                 h06_concur = varchar(15) = Concurso Público 
                 h06_dvalid = date = Validade 
                 h06_dprorr = date = Prorrogação 
                 h06_dpubl = date = Publicação 
                 h06_nrproc = varchar(16) = No. Processo 
                 ";
   //funcao construtor da classe 
   function cl_concur() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("concur"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->h06_refer = ($this->h06_refer == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_refer"]:$this->h06_refer);
       $this->h06_eaber = ($this->h06_eaber == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_eaber"]:$this->h06_eaber);
       if($this->h06_daber == ""){
         $this->h06_daber_dia = ($this->h06_daber_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_daber_dia"]:$this->h06_daber_dia);
         $this->h06_daber_mes = ($this->h06_daber_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_daber_mes"]:$this->h06_daber_mes);
         $this->h06_daber_ano = ($this->h06_daber_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_daber_ano"]:$this->h06_daber_ano);
         if($this->h06_daber_dia != ""){
            $this->h06_daber = $this->h06_daber_ano."-".$this->h06_daber_mes."-".$this->h06_daber_dia;
         }
       }
       $this->h06_ehomo = ($this->h06_ehomo == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_ehomo"]:$this->h06_ehomo);
       if($this->h06_dhomo == ""){
         $this->h06_dhomo_dia = ($this->h06_dhomo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dhomo_dia"]:$this->h06_dhomo_dia);
         $this->h06_dhomo_mes = ($this->h06_dhomo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dhomo_mes"]:$this->h06_dhomo_mes);
         $this->h06_dhomo_ano = ($this->h06_dhomo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dhomo_ano"]:$this->h06_dhomo_ano);
         if($this->h06_dhomo_dia != ""){
            $this->h06_dhomo = $this->h06_dhomo_ano."-".$this->h06_dhomo_mes."-".$this->h06_dhomo_dia;
         }
       }
       $this->h06_concur = ($this->h06_concur == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_concur"]:$this->h06_concur);
       if($this->h06_dvalid == ""){
         $this->h06_dvalid_dia = ($this->h06_dvalid_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dvalid_dia"]:$this->h06_dvalid_dia);
         $this->h06_dvalid_mes = ($this->h06_dvalid_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dvalid_mes"]:$this->h06_dvalid_mes);
         $this->h06_dvalid_ano = ($this->h06_dvalid_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dvalid_ano"]:$this->h06_dvalid_ano);
         if($this->h06_dvalid_dia != ""){
            $this->h06_dvalid = $this->h06_dvalid_ano."-".$this->h06_dvalid_mes."-".$this->h06_dvalid_dia;
         }
       }
       if($this->h06_dprorr == ""){
         $this->h06_dprorr_dia = ($this->h06_dprorr_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dprorr_dia"]:$this->h06_dprorr_dia);
         $this->h06_dprorr_mes = ($this->h06_dprorr_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dprorr_mes"]:$this->h06_dprorr_mes);
         $this->h06_dprorr_ano = ($this->h06_dprorr_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dprorr_ano"]:$this->h06_dprorr_ano);
         if($this->h06_dprorr_dia != ""){
            $this->h06_dprorr = $this->h06_dprorr_ano."-".$this->h06_dprorr_mes."-".$this->h06_dprorr_dia;
         }
       }
       if($this->h06_dpubl == ""){
         $this->h06_dpubl_dia = ($this->h06_dpubl_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dpubl_dia"]:$this->h06_dpubl_dia);
         $this->h06_dpubl_mes = ($this->h06_dpubl_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dpubl_mes"]:$this->h06_dpubl_mes);
         $this->h06_dpubl_ano = ($this->h06_dpubl_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_dpubl_ano"]:$this->h06_dpubl_ano);
         if($this->h06_dpubl_dia != ""){
            $this->h06_dpubl = $this->h06_dpubl_ano."-".$this->h06_dpubl_mes."-".$this->h06_dpubl_dia;
         }
       }
       $this->h06_nrproc = ($this->h06_nrproc == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_nrproc"]:$this->h06_nrproc);
     }else{
       $this->h06_refer = ($this->h06_refer == ""?@$GLOBALS["HTTP_POST_VARS"]["h06_refer"]:$this->h06_refer);
     }
   }
   // funcao para inclusao
   function incluir ($h06_refer){ 
      $this->atualizacampos();
     if($this->h06_eaber == null ){ 
       $this->erro_sql = " Campo Edital de Abertura nao Informado.";
       $this->erro_campo = "h06_eaber";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h06_daber == null ){ 
       $this->erro_sql = " Campo Data da Abertura nao Informado.";
       $this->erro_campo = "h06_daber_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h06_dhomo == null ){ 
       $this->h06_dhomo = "null";
     }
     if($this->h06_concur == null ){ 
       $this->erro_sql = " Campo Concurso Público nao Informado.";
       $this->erro_campo = "h06_concur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h06_dvalid == null ){ 
       $this->h06_dvalid = "null";
     }
     if($this->h06_dprorr == null ){ 
       $this->h06_dprorr = "null";
     }
     if($this->h06_dpubl == null ){ 
       $this->h06_dpubl = "null";
     }
       $this->h06_refer = $h06_refer; 
     if(($this->h06_refer == null) || ($this->h06_refer == "") ){ 
       $this->erro_sql = " Campo h06_refer nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into concur(
                                       h06_refer 
                                      ,h06_eaber 
                                      ,h06_daber 
                                      ,h06_ehomo 
                                      ,h06_dhomo 
                                      ,h06_concur 
                                      ,h06_dvalid 
                                      ,h06_dprorr 
                                      ,h06_dpubl 
                                      ,h06_nrproc 
                       )
                values (
                                $this->h06_refer 
                               ,'$this->h06_eaber' 
                               ,".($this->h06_daber == "null" || $this->h06_daber == ""?"null":"'".$this->h06_daber."'")." 
                               ,'$this->h06_ehomo' 
                               ,".($this->h06_dhomo == "null" || $this->h06_dhomo == ""?"null":"'".$this->h06_dhomo."'")." 
                               ,'$this->h06_concur' 
                               ,".($this->h06_dvalid == "null" || $this->h06_dvalid == ""?"null":"'".$this->h06_dvalid."'")." 
                               ,".($this->h06_dprorr == "null" || $this->h06_dprorr == ""?"null":"'".$this->h06_dprorr."'")." 
                               ,".($this->h06_dpubl == "null" || $this->h06_dpubl == ""?"null":"'".$this->h06_dpubl."'")." 
                               ,'$this->h06_nrproc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Concursos Publicos                                 ($this->h06_refer) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Concursos Publicos                                 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Concursos Publicos                                 ($this->h06_refer) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h06_refer;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h06_refer));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3830,'$this->h06_refer','I')");
       $resac = db_query("insert into db_acount values($acount,539,3830,'','".AddSlashes(pg_result($resaco,0,'h06_refer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,3831,'','".AddSlashes(pg_result($resaco,0,'h06_eaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,3832,'','".AddSlashes(pg_result($resaco,0,'h06_daber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,3833,'','".AddSlashes(pg_result($resaco,0,'h06_ehomo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,3834,'','".AddSlashes(pg_result($resaco,0,'h06_dhomo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,3835,'','".AddSlashes(pg_result($resaco,0,'h06_concur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,4621,'','".AddSlashes(pg_result($resaco,0,'h06_dvalid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,4622,'','".AddSlashes(pg_result($resaco,0,'h06_dprorr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,4623,'','".AddSlashes(pg_result($resaco,0,'h06_dpubl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,539,4624,'','".AddSlashes(pg_result($resaco,0,'h06_nrproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h06_refer=null) { 
      $this->atualizacampos();
     $sql = " update concur set ";
     $virgula = "";
     if(trim($this->h06_refer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_refer"])){ 
       $sql  .= $virgula." h06_refer = $this->h06_refer ";
       $virgula = ",";
       if(trim($this->h06_refer) == null ){ 
         $this->erro_sql = " Campo Codigo do Concurso nao Informado.";
         $this->erro_campo = "h06_refer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h06_eaber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_eaber"])){ 
       $sql  .= $virgula." h06_eaber = '$this->h06_eaber' ";
       $virgula = ",";
       if(trim($this->h06_eaber) == null ){ 
         $this->erro_sql = " Campo Edital de Abertura nao Informado.";
         $this->erro_campo = "h06_eaber";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h06_daber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_daber_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h06_daber_dia"] !="") ){ 
       $sql  .= $virgula." h06_daber = '$this->h06_daber' ";
       $virgula = ",";
       if(trim($this->h06_daber) == null ){ 
         $this->erro_sql = " Campo Data da Abertura nao Informado.";
         $this->erro_campo = "h06_daber_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h06_daber_dia"])){ 
         $sql  .= $virgula." h06_daber = null ";
         $virgula = ",";
         if(trim($this->h06_daber) == null ){ 
           $this->erro_sql = " Campo Data da Abertura nao Informado.";
           $this->erro_campo = "h06_daber_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h06_ehomo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_ehomo"])){ 
       $sql  .= $virgula." h06_ehomo = '$this->h06_ehomo' ";
       $virgula = ",";
     }
     if(trim($this->h06_dhomo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_dhomo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h06_dhomo_dia"] !="") ){ 
       $sql  .= $virgula." h06_dhomo = '$this->h06_dhomo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dhomo_dia"])){ 
         $sql  .= $virgula." h06_dhomo = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h06_concur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_concur"])){ 
       $sql  .= $virgula." h06_concur = '$this->h06_concur' ";
       $virgula = ",";
       if(trim($this->h06_concur) == null ){ 
         $this->erro_sql = " Campo Concurso Público nao Informado.";
         $this->erro_campo = "h06_concur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h06_dvalid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_dvalid_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h06_dvalid_dia"] !="") ){ 
       $sql  .= $virgula." h06_dvalid = '$this->h06_dvalid' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dvalid_dia"])){ 
         $sql  .= $virgula." h06_dvalid = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h06_dprorr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_dprorr_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h06_dprorr_dia"] !="") ){ 
       $sql  .= $virgula." h06_dprorr = '$this->h06_dprorr' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dprorr_dia"])){ 
         $sql  .= $virgula." h06_dprorr = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h06_dpubl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_dpubl_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h06_dpubl_dia"] !="") ){ 
       $sql  .= $virgula." h06_dpubl = '$this->h06_dpubl' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dpubl_dia"])){ 
         $sql  .= $virgula." h06_dpubl = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h06_nrproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h06_nrproc"])){ 
       $sql  .= $virgula." h06_nrproc = '$this->h06_nrproc' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h06_refer!=null){
       $sql .= " h06_refer = $this->h06_refer";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h06_refer));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3830,'$this->h06_refer','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_refer"]))
           $resac = db_query("insert into db_acount values($acount,539,3830,'".AddSlashes(pg_result($resaco,$conresaco,'h06_refer'))."','$this->h06_refer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_eaber"]))
           $resac = db_query("insert into db_acount values($acount,539,3831,'".AddSlashes(pg_result($resaco,$conresaco,'h06_eaber'))."','$this->h06_eaber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_daber"]))
           $resac = db_query("insert into db_acount values($acount,539,3832,'".AddSlashes(pg_result($resaco,$conresaco,'h06_daber'))."','$this->h06_daber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_ehomo"]))
           $resac = db_query("insert into db_acount values($acount,539,3833,'".AddSlashes(pg_result($resaco,$conresaco,'h06_ehomo'))."','$this->h06_ehomo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dhomo"]))
           $resac = db_query("insert into db_acount values($acount,539,3834,'".AddSlashes(pg_result($resaco,$conresaco,'h06_dhomo'))."','$this->h06_dhomo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_concur"]))
           $resac = db_query("insert into db_acount values($acount,539,3835,'".AddSlashes(pg_result($resaco,$conresaco,'h06_concur'))."','$this->h06_concur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dvalid"]))
           $resac = db_query("insert into db_acount values($acount,539,4621,'".AddSlashes(pg_result($resaco,$conresaco,'h06_dvalid'))."','$this->h06_dvalid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dprorr"]))
           $resac = db_query("insert into db_acount values($acount,539,4622,'".AddSlashes(pg_result($resaco,$conresaco,'h06_dprorr'))."','$this->h06_dprorr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_dpubl"]))
           $resac = db_query("insert into db_acount values($acount,539,4623,'".AddSlashes(pg_result($resaco,$conresaco,'h06_dpubl'))."','$this->h06_dpubl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h06_nrproc"]))
           $resac = db_query("insert into db_acount values($acount,539,4624,'".AddSlashes(pg_result($resaco,$conresaco,'h06_nrproc'))."','$this->h06_nrproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Concursos Publicos                                 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h06_refer;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Concursos Publicos                                 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h06_refer;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h06_refer;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h06_refer=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h06_refer));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3830,'$h06_refer','E')");
         $resac = db_query("insert into db_acount values($acount,539,3830,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_refer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,3831,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_eaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,3832,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_daber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,3833,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_ehomo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,3834,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_dhomo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,3835,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_concur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,4621,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_dvalid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,4622,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_dprorr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,4623,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_dpubl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,539,4624,'','".AddSlashes(pg_result($resaco,$iresaco,'h06_nrproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from concur
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h06_refer != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h06_refer = $h06_refer ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Concursos Publicos                                 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h06_refer;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Concursos Publicos                                 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h06_refer;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h06_refer;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:concur";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h06_refer=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from concur ";
     $sql2 = "";
     if($dbwhere==""){
       if($h06_refer!=null ){
         $sql2 .= " where concur.h06_refer = $h06_refer "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $h06_refer=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from concur ";
     $sql2 = "";
     if($dbwhere==""){
       if($h06_refer!=null ){
         $sql2 .= " where concur.h06_refer = $h06_refer "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>