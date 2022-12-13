<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE aprovconselho
class cl_aprovconselho { 
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
   var $ed253_i_codigo = 0; 
   var $ed253_i_diario = 0; 
   var $ed253_i_rechumano = null; 
   var $ed253_i_usuario = 0; 
   var $ed253_t_obs = null; 
   var $ed253_i_data = 0; 
   var $ed253_aprovconselhotipo = 0; 
   var $ed253_alterarnotafinal = null; 
   var $ed253_avaliacaoconselho = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed253_i_codigo = int8 = Código 
                 ed253_i_diario = int8 = Diário 
                 ed253_i_rechumano = int8 = Professor 
                 ed253_i_usuario = int8 = Usuário 
                 ed253_t_obs = text = Justificativa 
                 ed253_i_data = int8 = Data 
                 ed253_aprovconselhotipo = int4 = Tipo de Aprovação 
                 ed253_alterarnotafinal = int4 = Alternar nota final 
                 ed253_avaliacaoconselho = varchar(10) = Avaliação do Conselho 
                 ";
   //funcao construtor da classe 
   function cl_aprovconselho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aprovconselho"); 
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
       $this->ed253_i_codigo = ($this->ed253_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_codigo"]:$this->ed253_i_codigo);
       $this->ed253_i_diario = ($this->ed253_i_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_diario"]:$this->ed253_i_diario);
       $this->ed253_i_rechumano = ($this->ed253_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_rechumano"]:$this->ed253_i_rechumano);
       $this->ed253_i_usuario = ($this->ed253_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_usuario"]:$this->ed253_i_usuario);
       $this->ed253_t_obs = ($this->ed253_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_t_obs"]:$this->ed253_t_obs);
       $this->ed253_i_data = ($this->ed253_i_data == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_data"]:$this->ed253_i_data);
       $this->ed253_aprovconselhotipo = ($this->ed253_aprovconselhotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_aprovconselhotipo"]:$this->ed253_aprovconselhotipo);
       $this->ed253_alterarnotafinal = ($this->ed253_alterarnotafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_alterarnotafinal"]:$this->ed253_alterarnotafinal);
       $this->ed253_avaliacaoconselho = ($this->ed253_avaliacaoconselho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_avaliacaoconselho"]:$this->ed253_avaliacaoconselho);
     }else{
       $this->ed253_i_codigo = ($this->ed253_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed253_i_codigo"]:$this->ed253_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed253_i_codigo){ 
      $this->atualizacampos();
     if($this->ed253_i_diario == null ){
       $this->erro_sql = " Campo Diário nao Informado.";
       $this->erro_campo = "ed253_i_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed253_i_rechumano == null ){
       $this->ed253_i_rechumano = "null";
     }
     if($this->ed253_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed253_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed253_t_obs == null ){
       $this->erro_sql = " Campo Justificativa nao Informado.";
       $this->erro_campo = "ed253_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed253_i_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed253_i_data";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed253_aprovconselhotipo == null ){
       $this->erro_sql = " Campo Tipo de Aprovação nao Informado.";
       $this->erro_campo = "ed253_aprovconselhotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed253_alterarnotafinal == null ){ 
       $this->ed253_alterarnotafinal = "null";
     }
     if($ed253_i_codigo == "" || $ed253_i_codigo == null ){
       $result = db_query("select nextval('aprovconselho_ed253_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aprovconselho_ed253_i_codigo_seq do campo: ed253_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed253_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aprovconselho_ed253_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed253_i_codigo)){
         $this->erro_sql = " Campo ed253_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed253_i_codigo = $ed253_i_codigo; 
       }
     }
     if(($this->ed253_i_codigo == null) || ($this->ed253_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed253_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aprovconselho(
                                       ed253_i_codigo 
                                      ,ed253_i_diario 
                                      ,ed253_i_rechumano 
                                      ,ed253_i_usuario 
                                      ,ed253_t_obs 
                                      ,ed253_i_data 
                                      ,ed253_aprovconselhotipo 
                                      ,ed253_alterarnotafinal 
                                      ,ed253_avaliacaoconselho 
                       )
                values (
                                $this->ed253_i_codigo 
                               ,$this->ed253_i_diario 
                               ,$this->ed253_i_rechumano 
                               ,$this->ed253_i_usuario 
                               ,'$this->ed253_t_obs' 
                               ,$this->ed253_i_data 
                               ,$this->ed253_aprovconselhotipo 
                               ,$this->ed253_alterarnotafinal 
                               ,'$this->ed253_avaliacaoconselho' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alunos aprovados pelo conselho ($this->ed253_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alunos aprovados pelo conselho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alunos aprovados pelo conselho ($this->ed253_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed253_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed253_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12464,'$this->ed253_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2175,12464,'','".AddSlashes(pg_result($resaco,0,'ed253_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,12465,'','".AddSlashes(pg_result($resaco,0,'ed253_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,12466,'','".AddSlashes(pg_result($resaco,0,'ed253_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,12467,'','".AddSlashes(pg_result($resaco,0,'ed253_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,12469,'','".AddSlashes(pg_result($resaco,0,'ed253_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,12468,'','".AddSlashes(pg_result($resaco,0,'ed253_i_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,19621,'','".AddSlashes(pg_result($resaco,0,'ed253_aprovconselhotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,20683,'','".AddSlashes(pg_result($resaco,0,'ed253_alterarnotafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2175,20684,'','".AddSlashes(pg_result($resaco,0,'ed253_avaliacaoconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed253_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update aprovconselho set ";
     $virgula = "";
     if(trim($this->ed253_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_codigo"])){ 
       $sql  .= $virgula." ed253_i_codigo = $this->ed253_i_codigo ";
       $virgula = ",";
       if(trim($this->ed253_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed253_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_i_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_diario"])){ 
       $sql  .= $virgula." ed253_i_diario = $this->ed253_i_diario ";
       $virgula = ",";
       if(trim($this->ed253_i_diario) == null ){
         $this->erro_sql = " Campo Diário nao Informado.";
         $this->erro_campo = "ed253_i_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_rechumano"])){
        if(trim($this->ed253_i_rechumano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_rechumano"])){
           $this->ed253_i_rechumano = "null" ;
        }
       $sql  .= $virgula." ed253_i_rechumano = $this->ed253_i_rechumano ";
       $virgula = ",";
     }
     if(trim($this->ed253_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_usuario"])){ 
       $sql  .= $virgula." ed253_i_usuario = $this->ed253_i_usuario ";
       $virgula = ",";
       if(trim($this->ed253_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed253_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_t_obs"])){ 
       $sql  .= $virgula." ed253_t_obs = '$this->ed253_t_obs' ";
       $virgula = ",";
       if(trim($this->ed253_t_obs) == null ){
         $this->erro_sql = " Campo Justificativa nao Informado.";
         $this->erro_campo = "ed253_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_i_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_data"])){ 
       $sql  .= $virgula." ed253_i_data = $this->ed253_i_data ";
       $virgula = ",";
       if(trim($this->ed253_i_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed253_i_data";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_aprovconselhotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_aprovconselhotipo"])){ 
       $sql  .= $virgula." ed253_aprovconselhotipo = $this->ed253_aprovconselhotipo ";
       $virgula = ",";
       if(trim($this->ed253_aprovconselhotipo) == null ){
         $this->erro_sql = " Campo Tipo de Aprovação nao Informado.";
         $this->erro_campo = "ed253_aprovconselhotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed253_alterarnotafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_alterarnotafinal"])){ 
        if(trim($this->ed253_alterarnotafinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed253_alterarnotafinal"])){ 
           $this->ed253_alterarnotafinal = "null" ; 
        } 
       $sql  .= $virgula." ed253_alterarnotafinal = $this->ed253_alterarnotafinal ";
       $virgula = ",";
     }
     if(trim($this->ed253_avaliacaoconselho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed253_avaliacaoconselho"])){ 
       $sql  .= $virgula." ed253_avaliacaoconselho = '$this->ed253_avaliacaoconselho' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed253_i_codigo!=null){
       $sql .= " ed253_i_codigo = $this->ed253_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed253_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12464,'$this->ed253_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_codigo"]) || $this->ed253_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2175,12464,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_i_codigo'))."','$this->ed253_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_diario"]) || $this->ed253_i_diario != "")
             $resac = db_query("insert into db_acount values($acount,2175,12465,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_i_diario'))."','$this->ed253_i_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_rechumano"]) || $this->ed253_i_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,2175,12466,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_i_rechumano'))."','$this->ed253_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_usuario"]) || $this->ed253_i_usuario != "")
             $resac = db_query("insert into db_acount values($acount,2175,12467,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_i_usuario'))."','$this->ed253_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_t_obs"]) || $this->ed253_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,2175,12469,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_t_obs'))."','$this->ed253_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_i_data"]) || $this->ed253_i_data != "")
             $resac = db_query("insert into db_acount values($acount,2175,12468,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_i_data'))."','$this->ed253_i_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_aprovconselhotipo"]) || $this->ed253_aprovconselhotipo != "")
             $resac = db_query("insert into db_acount values($acount,2175,19621,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_aprovconselhotipo'))."','$this->ed253_aprovconselhotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_alterarnotafinal"]) || $this->ed253_alterarnotafinal != "")
             $resac = db_query("insert into db_acount values($acount,2175,20683,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_alterarnotafinal'))."','$this->ed253_alterarnotafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed253_avaliacaoconselho"]) || $this->ed253_avaliacaoconselho != "")
             $resac = db_query("insert into db_acount values($acount,2175,20684,'".AddSlashes(pg_result($resaco,$conresaco,'ed253_avaliacaoconselho'))."','$this->ed253_avaliacaoconselho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos aprovados pelo conselho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed253_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos aprovados pelo conselho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed253_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed253_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed253_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed253_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12464,'$ed253_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2175,12464,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,12465,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,12466,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,12467,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,12469,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,12468,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_i_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,19621,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_aprovconselhotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,20683,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_alterarnotafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2175,20684,'','".AddSlashes(pg_result($resaco,$iresaco,'ed253_avaliacaoconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aprovconselho
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed253_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed253_i_codigo = $ed253_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos aprovados pelo conselho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed253_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos aprovados pelo conselho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed253_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed253_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aprovconselho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed253_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aprovconselho ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aprovconselho.ed253_i_usuario";
     $sql .= "      left join rechumano  on  rechumano.ed20_i_codigo = aprovconselho.ed253_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = aprovconselho.ed253_i_diario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join aprovconselhotipo  on  aprovconselhotipo.ed122_sequencial = aprovconselho.ed253_aprovconselhotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed253_i_codigo!=null ){
         $sql2 .= " where aprovconselho.ed253_i_codigo = $ed253_i_codigo "; 
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
   // funcao do sql 
   function sql_query_file ( $ed253_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aprovconselho ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed253_i_codigo!=null ){
         $sql2 .= " where aprovconselho.ed253_i_codigo = $ed253_i_codigo "; 
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
   public function sql_query_tipo_aprovacao($ed253_i_codigo=null,$campos="*", $ordem=null,$dbwhere="") {
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
    $sql .= " from aprovconselho ";
    $sql .= "      inner join aprovconselhotipo  on  aprovconselhotipo.ed122_sequencial = aprovconselho.ed253_aprovconselhotipo";
    $sql2 = "";
    if($dbwhere==""){
      if($ed253_i_codigo!=null ){
        $sql2 .= " where aprovconselho.ed253_i_codigo = $ed253_i_codigo ";
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
