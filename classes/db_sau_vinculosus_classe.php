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

//MODULO: saude
//CLASSE DA ENTIDADE sau_vinculosus
class cl_sau_vinculosus { 
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
   var $sd50_i_codigo = 0; 
   var $sd50_i_unidade = 0; 
   var $sd50_v_banco = null; 
   var $sd50_v_agencia = null; 
   var $sd50_v_cc = null; 
   var $sd50_v_contratosus = null; 
   var $sd50_d_publicacao_dia = null; 
   var $sd50_d_publicacao_mes = null; 
   var $sd50_d_publicacao_ano = null; 
   var $sd50_d_publicacao = null; 
   var $sd50_v_contratosus2 = null; 
   var $sd50_d_publicacao2_dia = null; 
   var $sd50_d_publicacao2_mes = null; 
   var $sd50_d_publicacao2_ano = null; 
   var $sd50_d_publicacao2 = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd50_i_codigo = int4 = Código 
                 sd50_i_unidade = int4 = Unidade 
                 sd50_v_banco = varchar(3) = Banco 
                 sd50_v_agencia = varchar(5) = Agência 
                 sd50_v_cc = varchar(14) = Conta Corrente 
                 sd50_v_contratosus = varchar(60) = Contrato/Convênio Municipal 
                 sd50_d_publicacao = date = Data Publicação 
                 sd50_v_contratosus2 = varchar(60) = Contrato/Convênio Estadual 
                 sd50_d_publicacao2 = date = Data Publicação 
                 ";
   //funcao construtor da classe 
   function cl_sau_vinculosus() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_vinculosus"); 
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
       $this->sd50_i_codigo = ($this->sd50_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_i_codigo"]:$this->sd50_i_codigo);
       $this->sd50_i_unidade = ($this->sd50_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_i_unidade"]:$this->sd50_i_unidade);
       $this->sd50_v_banco = ($this->sd50_v_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_v_banco"]:$this->sd50_v_banco);
       $this->sd50_v_agencia = ($this->sd50_v_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_v_agencia"]:$this->sd50_v_agencia);
       $this->sd50_v_cc = ($this->sd50_v_cc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_v_cc"]:$this->sd50_v_cc);
       $this->sd50_v_contratosus = ($this->sd50_v_contratosus == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus"]:$this->sd50_v_contratosus);
       if($this->sd50_d_publicacao == ""){
         $this->sd50_d_publicacao_dia = ($this->sd50_d_publicacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_dia"]:$this->sd50_d_publicacao_dia);
         $this->sd50_d_publicacao_mes = ($this->sd50_d_publicacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_mes"]:$this->sd50_d_publicacao_mes);
         $this->sd50_d_publicacao_ano = ($this->sd50_d_publicacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_ano"]:$this->sd50_d_publicacao_ano);
         if($this->sd50_d_publicacao_dia != ""){
            $this->sd50_d_publicacao = $this->sd50_d_publicacao_ano."-".$this->sd50_d_publicacao_mes."-".$this->sd50_d_publicacao_dia;
         }
       }
       $this->sd50_v_contratosus2 = ($this->sd50_v_contratosus2 == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus2"]:$this->sd50_v_contratosus2);
       if($this->sd50_d_publicacao2 == ""){
         $this->sd50_d_publicacao2_dia = ($this->sd50_d_publicacao2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_dia"]:$this->sd50_d_publicacao2_dia);
         $this->sd50_d_publicacao2_mes = ($this->sd50_d_publicacao2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_mes"]:$this->sd50_d_publicacao2_mes);
         $this->sd50_d_publicacao2_ano = ($this->sd50_d_publicacao2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_ano"]:$this->sd50_d_publicacao2_ano);
         if($this->sd50_d_publicacao2_dia != ""){
            $this->sd50_d_publicacao2 = $this->sd50_d_publicacao2_ano."-".$this->sd50_d_publicacao2_mes."-".$this->sd50_d_publicacao2_dia;
         }
       }
     }else{
       $this->sd50_i_codigo = ($this->sd50_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd50_i_codigo"]:$this->sd50_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd50_i_codigo){ 
      $this->atualizacampos();
     if($this->sd50_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "sd50_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd50_d_publicacao == null ){ 
       $this->sd50_d_publicacao = "null";
     }
     if($this->sd50_d_publicacao2 == null ){ 
       $this->sd50_d_publicacao2 = "null";
     }
     if($sd50_i_codigo == "" || $sd50_i_codigo == null ){
       $result = db_query("select nextval('sau_vinculosus_sd50_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_vinculosus_sd50_i_codigo_seq do campo: sd50_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd50_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_vinculosus_sd50_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd50_i_codigo)){
         $this->erro_sql = " Campo sd50_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd50_i_codigo = $sd50_i_codigo; 
       }
     }
     if(($this->sd50_i_codigo == null) || ($this->sd50_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd50_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_vinculosus(
                                       sd50_i_codigo 
                                      ,sd50_i_unidade 
                                      ,sd50_v_banco 
                                      ,sd50_v_agencia 
                                      ,sd50_v_cc 
                                      ,sd50_v_contratosus 
                                      ,sd50_d_publicacao 
                                      ,sd50_v_contratosus2 
                                      ,sd50_d_publicacao2 
                       )
                values (
                                $this->sd50_i_codigo 
                               ,$this->sd50_i_unidade 
                               ,'$this->sd50_v_banco' 
                               ,'$this->sd50_v_agencia' 
                               ,'$this->sd50_v_cc' 
                               ,'$this->sd50_v_contratosus' 
                               ,".($this->sd50_d_publicacao == "null" || $this->sd50_d_publicacao == ""?"null":"'".$this->sd50_d_publicacao."'")." 
                               ,'$this->sd50_v_contratosus2' 
                               ,".($this->sd50_d_publicacao2 == "null" || $this->sd50_d_publicacao2 == ""?"null":"'".$this->sd50_d_publicacao2."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vínculo com o SUS ($this->sd50_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vínculo com o SUS já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vínculo com o SUS ($this->sd50_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd50_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd50_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11436,'$this->sd50_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1967,11436,'','".AddSlashes(pg_result($resaco,0,'sd50_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11437,'','".AddSlashes(pg_result($resaco,0,'sd50_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11438,'','".AddSlashes(pg_result($resaco,0,'sd50_v_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11439,'','".AddSlashes(pg_result($resaco,0,'sd50_v_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11440,'','".AddSlashes(pg_result($resaco,0,'sd50_v_cc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11441,'','".AddSlashes(pg_result($resaco,0,'sd50_v_contratosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11442,'','".AddSlashes(pg_result($resaco,0,'sd50_d_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11443,'','".AddSlashes(pg_result($resaco,0,'sd50_v_contratosus2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1967,11444,'','".AddSlashes(pg_result($resaco,0,'sd50_d_publicacao2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd50_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_vinculosus set ";
     $virgula = "";
     if(trim($this->sd50_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_i_codigo"])){ 
       $sql  .= $virgula." sd50_i_codigo = $this->sd50_i_codigo ";
       $virgula = ",";
       if(trim($this->sd50_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd50_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd50_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_i_unidade"])){ 
       $sql  .= $virgula." sd50_i_unidade = $this->sd50_i_unidade ";
       $virgula = ",";
       if(trim($this->sd50_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "sd50_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd50_v_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_banco"])){ 
       $sql  .= $virgula." sd50_v_banco = '$this->sd50_v_banco' ";
       $virgula = ",";
     }
     if(trim($this->sd50_v_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_agencia"])){ 
       $sql  .= $virgula." sd50_v_agencia = '$this->sd50_v_agencia' ";
       $virgula = ",";
     }
     if(trim($this->sd50_v_cc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_cc"])){ 
       $sql  .= $virgula." sd50_v_cc = '$this->sd50_v_cc' ";
       $virgula = ",";
     }
     if(trim($this->sd50_v_contratosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus"])){ 
       $sql  .= $virgula." sd50_v_contratosus = '$this->sd50_v_contratosus' ";
       $virgula = ",";
     }
     if(trim($this->sd50_d_publicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_dia"] !="") ){ 
       $sql  .= $virgula." sd50_d_publicacao = '$this->sd50_d_publicacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao_dia"])){ 
         $sql  .= $virgula." sd50_d_publicacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd50_v_contratosus2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus2"])){ 
       $sql  .= $virgula." sd50_v_contratosus2 = '$this->sd50_v_contratosus2' ";
       $virgula = ",";
     }
     if(trim($this->sd50_d_publicacao2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_dia"] !="") ){ 
       $sql  .= $virgula." sd50_d_publicacao2 = '$this->sd50_d_publicacao2' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2_dia"])){ 
         $sql  .= $virgula." sd50_d_publicacao2 = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($sd50_i_codigo!=null){
       $sql .= " sd50_i_codigo = $this->sd50_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd50_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11436,'$this->sd50_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1967,11436,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_i_codigo'))."','$this->sd50_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_i_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1967,11437,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_i_unidade'))."','$this->sd50_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_banco"]))
           $resac = db_query("insert into db_acount values($acount,1967,11438,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_v_banco'))."','$this->sd50_v_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_agencia"]))
           $resac = db_query("insert into db_acount values($acount,1967,11439,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_v_agencia'))."','$this->sd50_v_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_cc"]))
           $resac = db_query("insert into db_acount values($acount,1967,11440,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_v_cc'))."','$this->sd50_v_cc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus"]))
           $resac = db_query("insert into db_acount values($acount,1967,11441,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_v_contratosus'))."','$this->sd50_v_contratosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao"]))
           $resac = db_query("insert into db_acount values($acount,1967,11442,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_d_publicacao'))."','$this->sd50_d_publicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_v_contratosus2"]))
           $resac = db_query("insert into db_acount values($acount,1967,11443,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_v_contratosus2'))."','$this->sd50_v_contratosus2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd50_d_publicacao2"]))
           $resac = db_query("insert into db_acount values($acount,1967,11444,'".AddSlashes(pg_result($resaco,$conresaco,'sd50_d_publicacao2'))."','$this->sd50_d_publicacao2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo com o SUS nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd50_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo com o SUS nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd50_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd50_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11436,'$sd50_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1967,11436,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11437,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11438,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_v_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11439,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_v_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11440,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_v_cc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11441,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_v_contratosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11442,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_d_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11443,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_v_contratosus2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1967,11444,'','".AddSlashes(pg_result($resaco,$iresaco,'sd50_d_publicacao2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_vinculosus
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd50_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd50_i_codigo = $sd50_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo com o SUS nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd50_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo com o SUS nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd50_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_vinculosus";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd50_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_vinculosus ";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = sau_vinculosus.sd50_i_unidade";
     $sql .= "       left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm and  cgm.z01_numcgm = unidades.sd02_i_diretor";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "       left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "       left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "       left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "       left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "       left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "       left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "       left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "       left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql2 = "";
     if($dbwhere==""){
       if($sd50_i_codigo!=null ){
         $sql2 .= " where sau_vinculosus.sd50_i_codigo = $sd50_i_codigo "; 
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
   function sql_query_file ( $sd50_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_vinculosus ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd50_i_codigo!=null ){
         $sql2 .= " where sau_vinculosus.sd50_i_codigo = $sd50_i_codigo "; 
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