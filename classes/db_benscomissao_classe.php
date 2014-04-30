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
//CLASSE DA ENTIDADE benscomissao
class cl_benscomissao { 
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
   var $t60_codcom = 0; 
   var $t60_dataini_dia = null; 
   var $t60_dataini_mes = null; 
   var $t60_dataini_ano = null; 
   var $t60_dataini = null; 
   var $t60_datafim_dia = null; 
   var $t60_datafim_mes = null; 
   var $t60_datafim_ano = null; 
   var $t60_datafim = null; 
   var $t60_id_usuario = 0; 
   var $t60_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t60_codcom = int8 = Código da comissão 
                 t60_dataini = date = Data de início 
                 t60_datafim = date = Data final 
                 t60_id_usuario = int4 = Cod. Usuário 
                 t60_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_benscomissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benscomissao"); 
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
       $this->t60_codcom = ($this->t60_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_codcom"]:$this->t60_codcom);
       if($this->t60_dataini == ""){
         $this->t60_dataini_dia = ($this->t60_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_dataini_dia"]:$this->t60_dataini_dia);
         $this->t60_dataini_mes = ($this->t60_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_dataini_mes"]:$this->t60_dataini_mes);
         $this->t60_dataini_ano = ($this->t60_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_dataini_ano"]:$this->t60_dataini_ano);
         if($this->t60_dataini_dia != ""){
            $this->t60_dataini = $this->t60_dataini_ano."-".$this->t60_dataini_mes."-".$this->t60_dataini_dia;
         }
       }
       if($this->t60_datafim == ""){
         $this->t60_datafim_dia = ($this->t60_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_datafim_dia"]:$this->t60_datafim_dia);
         $this->t60_datafim_mes = ($this->t60_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_datafim_mes"]:$this->t60_datafim_mes);
         $this->t60_datafim_ano = ($this->t60_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_datafim_ano"]:$this->t60_datafim_ano);
         if($this->t60_datafim_dia != ""){
            $this->t60_datafim = $this->t60_datafim_ano."-".$this->t60_datafim_mes."-".$this->t60_datafim_dia;
         }
       }
       $this->t60_id_usuario = ($this->t60_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_id_usuario"]:$this->t60_id_usuario);
       $this->t60_instit = ($this->t60_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_instit"]:$this->t60_instit);
     }else{
       $this->t60_codcom = ($this->t60_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["t60_codcom"]:$this->t60_codcom);
     }
   }
   // funcao para inclusao
   function incluir ($t60_codcom){ 
      $this->atualizacampos();
     if($this->t60_dataini == null ){ 
       $this->erro_sql = " Campo Data de início nao Informado.";
       $this->erro_campo = "t60_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t60_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "t60_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t60_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t60_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t60_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t60_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t60_codcom == "" || $t60_codcom == null ){
       $result = db_query("select nextval('benscomissao_t60_codcom_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benscomissao_t60_codcom_seq do campo: t60_codcom"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t60_codcom = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benscomissao_t60_codcom_seq");
       if(($result != false) && (pg_result($result,0,0) < $t60_codcom)){
         $this->erro_sql = " Campo t60_codcom maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t60_codcom = $t60_codcom; 
       }
     }
     if(($this->t60_codcom == null) || ($this->t60_codcom == "") ){ 
       $this->erro_sql = " Campo t60_codcom nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benscomissao(
                                       t60_codcom 
                                      ,t60_dataini 
                                      ,t60_datafim 
                                      ,t60_id_usuario 
                                      ,t60_instit 
                       )
                values (
                                $this->t60_codcom 
                               ,".($this->t60_dataini == "null" || $this->t60_dataini == ""?"null":"'".$this->t60_dataini."'")." 
                               ,".($this->t60_datafim == "null" || $this->t60_datafim == ""?"null":"'".$this->t60_datafim."'")." 
                               ,$this->t60_id_usuario 
                               ,$this->t60_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Comissao de bens ($this->t60_codcom) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Comissao de bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Comissao de bens ($this->t60_codcom) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t60_codcom;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t60_codcom));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5795,'$this->t60_codcom','I')");
       $resac = db_query("insert into db_acount values($acount,920,5795,'','".AddSlashes(pg_result($resaco,0,'t60_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,920,5796,'','".AddSlashes(pg_result($resaco,0,'t60_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,920,5797,'','".AddSlashes(pg_result($resaco,0,'t60_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,920,5798,'','".AddSlashes(pg_result($resaco,0,'t60_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,920,9804,'','".AddSlashes(pg_result($resaco,0,'t60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t60_codcom=null) { 
      $this->atualizacampos();
     $sql = " update benscomissao set ";
     $virgula = "";
     if(trim($this->t60_codcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t60_codcom"])){ 
       $sql  .= $virgula." t60_codcom = $this->t60_codcom ";
       $virgula = ",";
       if(trim($this->t60_codcom) == null ){ 
         $this->erro_sql = " Campo Código da comissão nao Informado.";
         $this->erro_campo = "t60_codcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t60_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t60_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t60_dataini_dia"] !="") ){ 
       $sql  .= $virgula." t60_dataini = '$this->t60_dataini' ";
       $virgula = ",";
       if(trim($this->t60_dataini) == null ){ 
         $this->erro_sql = " Campo Data de início nao Informado.";
         $this->erro_campo = "t60_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t60_dataini_dia"])){ 
         $sql  .= $virgula." t60_dataini = null ";
         $virgula = ",";
         if(trim($this->t60_dataini) == null ){ 
           $this->erro_sql = " Campo Data de início nao Informado.";
           $this->erro_campo = "t60_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t60_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t60_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t60_datafim_dia"] !="") ){ 
       $sql  .= $virgula." t60_datafim = '$this->t60_datafim' ";
       $virgula = ",";
       if(trim($this->t60_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "t60_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t60_datafim_dia"])){ 
         $sql  .= $virgula." t60_datafim = null ";
         $virgula = ",";
         if(trim($this->t60_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "t60_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t60_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t60_id_usuario"])){ 
       $sql  .= $virgula." t60_id_usuario = $this->t60_id_usuario ";
       $virgula = ",";
       if(trim($this->t60_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t60_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t60_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t60_instit"])){ 
       $sql  .= $virgula." t60_instit = $this->t60_instit ";
       $virgula = ",";
       if(trim($this->t60_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t60_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t60_codcom!=null){
       $sql .= " t60_codcom = $this->t60_codcom";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t60_codcom));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5795,'$this->t60_codcom','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t60_codcom"]))
           $resac = db_query("insert into db_acount values($acount,920,5795,'".AddSlashes(pg_result($resaco,$conresaco,'t60_codcom'))."','$this->t60_codcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t60_dataini"]))
           $resac = db_query("insert into db_acount values($acount,920,5796,'".AddSlashes(pg_result($resaco,$conresaco,'t60_dataini'))."','$this->t60_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t60_datafim"]))
           $resac = db_query("insert into db_acount values($acount,920,5797,'".AddSlashes(pg_result($resaco,$conresaco,'t60_datafim'))."','$this->t60_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t60_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,920,5798,'".AddSlashes(pg_result($resaco,$conresaco,'t60_id_usuario'))."','$this->t60_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t60_instit"]))
           $resac = db_query("insert into db_acount values($acount,920,9804,'".AddSlashes(pg_result($resaco,$conresaco,'t60_instit'))."','$this->t60_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissao de bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t60_codcom;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissao de bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t60_codcom;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t60_codcom;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t60_codcom=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t60_codcom));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5795,'$t60_codcom','E')");
         $resac = db_query("insert into db_acount values($acount,920,5795,'','".AddSlashes(pg_result($resaco,$iresaco,'t60_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,920,5796,'','".AddSlashes(pg_result($resaco,$iresaco,'t60_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,920,5797,'','".AddSlashes(pg_result($resaco,$iresaco,'t60_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,920,5798,'','".AddSlashes(pg_result($resaco,$iresaco,'t60_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,920,9804,'','".AddSlashes(pg_result($resaco,$iresaco,'t60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benscomissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t60_codcom != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t60_codcom = $t60_codcom ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissao de bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t60_codcom;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissao de bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t60_codcom;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t60_codcom;
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
        $this->erro_sql   = "Record Vazio na Tabela:benscomissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t60_codcom=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscomissao ";
     $sql .= "      inner join db_config  on  db_config.codigo = benscomissao.t60_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benscomissao.t60_id_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($t60_codcom!=null ){
         $sql2 .= " where benscomissao.t60_codcom = $t60_codcom "; 
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
   function sql_query_file ( $t60_codcom=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscomissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($t60_codcom!=null ){
         $sql2 .= " where benscomissao.t60_codcom = $t60_codcom "; 
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