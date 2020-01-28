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

//MODULO: patrim
//CLASSE DA ENTIDADE apolice
class cl_apolice { 
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
   var $t81_codapo = 0; 
   var $t81_codseg = 0; 
   var $t81_apolice = null; 
   var $t81_venc_dia = null; 
   var $t81_venc_mes = null; 
   var $t81_venc_ano = null; 
   var $t81_venc = null; 
   var $t81_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t81_codapo = int8 = Código apólice 
                 t81_codseg = int8 = Código da seguradora 
                 t81_apolice = varchar(50) = Apólice 
                 t81_venc = date = Vencimento da apólice 
                 t81_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_apolice() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("apolice"); 
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
       $this->t81_codapo = ($this->t81_codapo == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_codapo"]:$this->t81_codapo);
       $this->t81_codseg = ($this->t81_codseg == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_codseg"]:$this->t81_codseg);
       $this->t81_apolice = ($this->t81_apolice == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_apolice"]:$this->t81_apolice);
       if($this->t81_venc == ""){
         $this->t81_venc_dia = ($this->t81_venc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_venc_dia"]:$this->t81_venc_dia);
         $this->t81_venc_mes = ($this->t81_venc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_venc_mes"]:$this->t81_venc_mes);
         $this->t81_venc_ano = ($this->t81_venc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_venc_ano"]:$this->t81_venc_ano);
         if($this->t81_venc_dia != ""){
            $this->t81_venc = $this->t81_venc_ano."-".$this->t81_venc_mes."-".$this->t81_venc_dia;
         }
       }
       $this->t81_instit = ($this->t81_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_instit"]:$this->t81_instit);
     }else{
       $this->t81_codapo = ($this->t81_codapo == ""?@$GLOBALS["HTTP_POST_VARS"]["t81_codapo"]:$this->t81_codapo);
     }
   }
   // funcao para inclusao
   function incluir ($t81_codapo){ 
      $this->atualizacampos();
     if($this->t81_codseg == null ){ 
       $this->erro_sql = " Campo Código da seguradora nao Informado.";
       $this->erro_campo = "t81_codseg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t81_apolice == null ){ 
       $this->erro_sql = " Campo Apólice nao Informado.";
       $this->erro_campo = "t81_apolice";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t81_venc == null ){ 
       $this->erro_sql = " Campo Vencimento da apólice nao Informado.";
       $this->erro_campo = "t81_venc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t81_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t81_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t81_codapo == "" || $t81_codapo == null ){
       $result = db_query("select nextval('apolice_t81_codapo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: apolice_t81_codapo_seq do campo: t81_codapo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t81_codapo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from apolice_t81_codapo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t81_codapo)){
         $this->erro_sql = " Campo t81_codapo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t81_codapo = $t81_codapo; 
       }
     }
     if(($this->t81_codapo == null) || ($this->t81_codapo == "") ){ 
       $this->erro_sql = " Campo t81_codapo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into apolice(
                                       t81_codapo 
                                      ,t81_codseg 
                                      ,t81_apolice 
                                      ,t81_venc 
                                      ,t81_instit 
                       )
                values (
                                $this->t81_codapo 
                               ,$this->t81_codseg 
                               ,'$this->t81_apolice' 
                               ,".($this->t81_venc == "null" || $this->t81_venc == ""?"null":"'".$this->t81_venc."'")." 
                               ,$this->t81_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Apólice ($this->t81_codapo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Apólice já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Apólice ($this->t81_codapo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t81_codapo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t81_codapo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5816,'$this->t81_codapo','I')");
       $resac = db_query("insert into db_acount values($acount,928,5816,'','".AddSlashes(pg_result($resaco,0,'t81_codapo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,928,5817,'','".AddSlashes(pg_result($resaco,0,'t81_codseg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,928,5818,'','".AddSlashes(pg_result($resaco,0,'t81_apolice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,928,5819,'','".AddSlashes(pg_result($resaco,0,'t81_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,928,9805,'','".AddSlashes(pg_result($resaco,0,'t81_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t81_codapo=null) { 
      $this->atualizacampos();
     $sql = " update apolice set ";
     $virgula = "";
     if(trim($this->t81_codapo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t81_codapo"])){ 
       $sql  .= $virgula." t81_codapo = $this->t81_codapo ";
       $virgula = ",";
       if(trim($this->t81_codapo) == null ){ 
         $this->erro_sql = " Campo Código apólice nao Informado.";
         $this->erro_campo = "t81_codapo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t81_codseg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t81_codseg"])){ 
       $sql  .= $virgula." t81_codseg = $this->t81_codseg ";
       $virgula = ",";
       if(trim($this->t81_codseg) == null ){ 
         $this->erro_sql = " Campo Código da seguradora nao Informado.";
         $this->erro_campo = "t81_codseg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t81_apolice)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t81_apolice"])){ 
       $sql  .= $virgula." t81_apolice = '$this->t81_apolice' ";
       $virgula = ",";
       if(trim($this->t81_apolice) == null ){ 
         $this->erro_sql = " Campo Apólice nao Informado.";
         $this->erro_campo = "t81_apolice";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t81_venc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t81_venc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t81_venc_dia"] !="") ){ 
       $sql  .= $virgula." t81_venc = '$this->t81_venc' ";
       $virgula = ",";
       if(trim($this->t81_venc) == null ){ 
         $this->erro_sql = " Campo Vencimento da apólice nao Informado.";
         $this->erro_campo = "t81_venc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t81_venc_dia"])){ 
         $sql  .= $virgula." t81_venc = null ";
         $virgula = ",";
         if(trim($this->t81_venc) == null ){ 
           $this->erro_sql = " Campo Vencimento da apólice nao Informado.";
           $this->erro_campo = "t81_venc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t81_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t81_instit"])){ 
       $sql  .= $virgula." t81_instit = $this->t81_instit ";
       $virgula = ",";
       if(trim($this->t81_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t81_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t81_codapo!=null){
       $sql .= " t81_codapo = $this->t81_codapo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t81_codapo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5816,'$this->t81_codapo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t81_codapo"]))
           $resac = db_query("insert into db_acount values($acount,928,5816,'".AddSlashes(pg_result($resaco,$conresaco,'t81_codapo'))."','$this->t81_codapo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t81_codseg"]))
           $resac = db_query("insert into db_acount values($acount,928,5817,'".AddSlashes(pg_result($resaco,$conresaco,'t81_codseg'))."','$this->t81_codseg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t81_apolice"]))
           $resac = db_query("insert into db_acount values($acount,928,5818,'".AddSlashes(pg_result($resaco,$conresaco,'t81_apolice'))."','$this->t81_apolice',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t81_venc"]))
           $resac = db_query("insert into db_acount values($acount,928,5819,'".AddSlashes(pg_result($resaco,$conresaco,'t81_venc'))."','$this->t81_venc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t81_instit"]))
           $resac = db_query("insert into db_acount values($acount,928,9805,'".AddSlashes(pg_result($resaco,$conresaco,'t81_instit'))."','$this->t81_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apólice nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t81_codapo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apólice nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t81_codapo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t81_codapo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t81_codapo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t81_codapo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5816,'$t81_codapo','E')");
         $resac = db_query("insert into db_acount values($acount,928,5816,'','".AddSlashes(pg_result($resaco,$iresaco,'t81_codapo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,928,5817,'','".AddSlashes(pg_result($resaco,$iresaco,'t81_codseg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,928,5818,'','".AddSlashes(pg_result($resaco,$iresaco,'t81_apolice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,928,5819,'','".AddSlashes(pg_result($resaco,$iresaco,'t81_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,928,9805,'','".AddSlashes(pg_result($resaco,$iresaco,'t81_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from apolice
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t81_codapo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t81_codapo = $t81_codapo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apólice nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t81_codapo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apólice nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t81_codapo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t81_codapo;
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
        $this->erro_sql   = "Record Vazio na Tabela:apolice";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t81_codapo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from apolice ";
     $sql .= "      inner join db_config   on db_config.codigo = apolice.t81_instit";
     $sql .= "      inner join seguradoras on seguradoras.t80_segura = apolice.t81_codseg";
     $sql .= "      inner join cgm         on cgm.z01_numcgm = seguradoras.t80_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($t81_codapo!=null ){
         $sql2 .= " where apolice.t81_codapo = $t81_codapo "; 
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
   function sql_query_file ( $t81_codapo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from apolice ";
     $sql2 = "";
     if($dbwhere==""){
       if($t81_codapo!=null ){
         $sql2 .= " where apolice.t81_codapo = $t81_codapo "; 
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