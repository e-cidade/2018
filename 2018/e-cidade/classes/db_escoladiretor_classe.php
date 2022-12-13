<?
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

//MODULO: educação
//CLASSE DA ENTIDADE escoladiretor
class cl_escoladiretor {
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
   var $ed254_i_codigo = 0;
   var $ed254_i_rechumano = 0;
   var $ed254_i_escola = 0;
   var $ed254_i_turno = 0;
   var $ed254_i_atolegal = 'null';
   var $ed254_c_email = null;
   var $ed254_d_dataini_dia = null;
   var $ed254_d_dataini_mes = null;
   var $ed254_d_dataini_ano = null;
   var $ed254_d_dataini = null;
   var $ed254_d_datafim_dia = null;
   var $ed254_d_datafim_mes = null;
   var $ed254_d_datafim_ano = null;
   var $ed254_d_datafim = null;
   var $ed254_c_tipo = null;
   var $ed254_i_usuario = 0;
   var $ed254_d_datacad_dia = null;
   var $ed254_d_datacad_mes = null;
   var $ed254_d_datacad_ano = null;
   var $ed254_d_datacad = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed254_i_codigo = int8 = Código
                 ed254_i_rechumano = int8 = Diretor
                 ed254_i_escola = int8 = Escola
                 ed254_i_turno = int8 = Turno
                 ed254_i_atolegal = int8 = Ato Legal
                 ed254_c_email = char(100) = Email
                 ed254_d_dataini = date = Data Inicial do Exercício
                 ed254_d_datafim = date = Data Final do Exercício
                 ed254_c_tipo = char(1) = Situação do Exercício
                 ed254_i_usuario = int8 = Usuário
                 ed254_d_datacad = date = Data do Cadastro
                 ";
   //funcao construtor da classe
   function cl_escoladiretor() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escoladiretor");
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
       $this->ed254_i_codigo = ($this->ed254_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]:$this->ed254_i_codigo);
       $this->ed254_i_rechumano = ($this->ed254_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"]:$this->ed254_i_rechumano);
       $this->ed254_i_escola = ($this->ed254_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"]:$this->ed254_i_escola);
       $this->ed254_i_turno = ($this->ed254_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"]:$this->ed254_i_turno);
       $this->ed254_i_atolegal = ($this->ed254_i_atolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"]:$this->ed254_i_atolegal);
       $this->ed254_c_email = ($this->ed254_c_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_c_email"]:$this->ed254_c_email);
       if($this->ed254_d_dataini == ""){
         $this->ed254_d_dataini_dia = ($this->ed254_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"]:$this->ed254_d_dataini_dia);
         $this->ed254_d_dataini_mes = ($this->ed254_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_mes"]:$this->ed254_d_dataini_mes);
         $this->ed254_d_dataini_ano = ($this->ed254_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_ano"]:$this->ed254_d_dataini_ano);
         if($this->ed254_d_dataini_dia != ""){
            $this->ed254_d_dataini = $this->ed254_d_dataini_ano."-".$this->ed254_d_dataini_mes."-".$this->ed254_d_dataini_dia;
         }
       }
       if($this->ed254_d_datafim == ""){
         $this->ed254_d_datafim_dia = ($this->ed254_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"]:$this->ed254_d_datafim_dia);
         $this->ed254_d_datafim_mes = ($this->ed254_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_mes"]:$this->ed254_d_datafim_mes);
         $this->ed254_d_datafim_ano = ($this->ed254_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_ano"]:$this->ed254_d_datafim_ano);
         if($this->ed254_d_datafim_dia != ""){
            $this->ed254_d_datafim = $this->ed254_d_datafim_ano."-".$this->ed254_d_datafim_mes."-".$this->ed254_d_datafim_dia;
         }
       }
       $this->ed254_c_tipo = ($this->ed254_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"]:$this->ed254_c_tipo);
       $this->ed254_i_usuario = ($this->ed254_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"]:$this->ed254_i_usuario);
       if($this->ed254_d_datacad == ""){
         $this->ed254_d_datacad_dia = ($this->ed254_d_datacad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"]:$this->ed254_d_datacad_dia);
         $this->ed254_d_datacad_mes = ($this->ed254_d_datacad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_mes"]:$this->ed254_d_datacad_mes);
         $this->ed254_d_datacad_ano = ($this->ed254_d_datacad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_ano"]:$this->ed254_d_datacad_ano);
         if($this->ed254_d_datacad_dia != ""){
            $this->ed254_d_datacad = $this->ed254_d_datacad_ano."-".$this->ed254_d_datacad_mes."-".$this->ed254_d_datacad_dia;
         }
       }
     }else{
       $this->ed254_i_codigo = ($this->ed254_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]:$this->ed254_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed254_i_codigo){
      $this->atualizacampos();
     if($this->ed254_i_rechumano == null ){
       $this->erro_sql = " Campo Diretor nao Informado.";
       $this->erro_campo = "ed254_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed254_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_turno == null ){
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed254_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if( empty($this->ed254_i_atolegal) ) {
       $this->ed254_i_atolegal = 'null';
     }

     if($this->ed254_d_dataini == null ){
       $this->erro_sql = " Campo Data Inicial do Exercício nao Informado.";
       $this->erro_campo = "ed254_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_d_datafim == null ){
       $this->ed254_d_datafim = "null";
     }
     if($this->ed254_c_tipo == null ){
       $this->erro_sql = " Campo Situação do Exercício nao Informado.";
       $this->erro_campo = "ed254_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed254_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_d_datacad == null ){
       $this->erro_sql = " Campo Data do Cadastro nao Informado.";
       $this->erro_campo = "ed254_d_datacad_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed254_i_codigo == "" || $ed254_i_codigo == null ){
       $result = db_query("select nextval('escoladiretor_ed254_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escoladiretor_ed254_i_codigo_seq do campo: ed254_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed254_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from escoladiretor_ed254_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed254_i_codigo)){
         $this->erro_sql = " Campo ed254_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed254_i_codigo = $ed254_i_codigo;
       }
     }
     if(($this->ed254_i_codigo == null) || ($this->ed254_i_codigo == "") ){
       $this->erro_sql = " Campo ed254_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escoladiretor(
                                       ed254_i_codigo
                                      ,ed254_i_rechumano
                                      ,ed254_i_escola
                                      ,ed254_i_turno
                                      ,ed254_i_atolegal
                                      ,ed254_c_email
                                      ,ed254_d_dataini
                                      ,ed254_d_datafim
                                      ,ed254_c_tipo
                                      ,ed254_i_usuario
                                      ,ed254_d_datacad
                       )
                values (
                                $this->ed254_i_codigo
                               ,$this->ed254_i_rechumano
                               ,$this->ed254_i_escola
                               ,$this->ed254_i_turno
                               ,$this->ed254_i_atolegal
                               ,'$this->ed254_c_email'
                               ,".($this->ed254_d_dataini == "null" || $this->ed254_d_dataini == ""?"null":"'".$this->ed254_d_dataini."'")."
                               ,".($this->ed254_d_datafim == "null" || $this->ed254_d_datafim == ""?"null":"'".$this->ed254_d_datafim."'")."
                               ,'$this->ed254_c_tipo'
                               ,$this->ed254_i_usuario
                               ,".($this->ed254_d_datacad == "null" || $this->ed254_d_datacad == ""?"null":"'".$this->ed254_d_datacad."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diretores da Escola ($this->ed254_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diretores da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diretores da Escola ($this->ed254_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed254_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12514,'$this->ed254_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2183,12514,'','".AddSlashes(pg_result($resaco,0,'ed254_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12515,'','".AddSlashes(pg_result($resaco,0,'ed254_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12516,'','".AddSlashes(pg_result($resaco,0,'ed254_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12517,'','".AddSlashes(pg_result($resaco,0,'ed254_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12551,'','".AddSlashes(pg_result($resaco,0,'ed254_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12557,'','".AddSlashes(pg_result($resaco,0,'ed254_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12553,'','".AddSlashes(pg_result($resaco,0,'ed254_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12554,'','".AddSlashes(pg_result($resaco,0,'ed254_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12555,'','".AddSlashes(pg_result($resaco,0,'ed254_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12552,'','".AddSlashes(pg_result($resaco,0,'ed254_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2183,12556,'','".AddSlashes(pg_result($resaco,0,'ed254_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed254_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update escoladiretor set ";
     $virgula = "";
     if(trim($this->ed254_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"])){
       $sql  .= $virgula." ed254_i_codigo = $this->ed254_i_codigo ";
       $virgula = ",";
       if(trim($this->ed254_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed254_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"])){
       $sql  .= $virgula." ed254_i_rechumano = $this->ed254_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed254_i_rechumano) == null ){
         $this->erro_sql = " Campo Diretor nao Informado.";
         $this->erro_campo = "ed254_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"])){
       $sql  .= $virgula." ed254_i_escola = $this->ed254_i_escola ";
       $virgula = ",";
       if(trim($this->ed254_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed254_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"])){
       $sql  .= $virgula." ed254_i_turno = $this->ed254_i_turno ";
       $virgula = ",";
       if(trim($this->ed254_i_turno) == null ){
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed254_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if( empty($this->ed254_i_atolegal) || empty($GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"]) ){
       $this->ed254_i_atolegal = 'null';
     }
     $sql .= $virgula." ed254_i_atolegal = {$this->ed254_i_atolegal}";

     if(trim($this->ed254_c_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_email"])){
       $sql  .= $virgula." ed254_c_email = '$this->ed254_c_email' ";
       $virgula = ",";
     }
     if(trim($this->ed254_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"] !="") ){
       $sql  .= $virgula." ed254_d_dataini = '$this->ed254_d_dataini' ";
       $virgula = ",";
       if(trim($this->ed254_d_dataini) == null ){
         $this->erro_sql = " Campo Data Inicial do Exercício nao Informado.";
         $this->erro_campo = "ed254_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"])){
         $sql  .= $virgula." ed254_d_dataini = null ";
         $virgula = ",";
         if(trim($this->ed254_d_dataini) == null ){
           $this->erro_sql = " Campo Data Inicial do Exercício nao Informado.";
           $this->erro_campo = "ed254_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed254_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"] !="") ){
       $sql  .= $virgula." ed254_d_datafim = '$this->ed254_d_datafim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"])){
         $sql  .= $virgula." ed254_d_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed254_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"])){
       $sql  .= $virgula." ed254_c_tipo = '$this->ed254_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed254_c_tipo) == null ){
         $this->erro_sql = " Campo Situação do Exercício nao Informado.";
         $this->erro_campo = "ed254_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"])){
       $sql  .= $virgula." ed254_i_usuario = $this->ed254_i_usuario ";
       $virgula = ",";
       if(trim($this->ed254_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed254_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_d_datacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"] !="") ){
       $sql  .= $virgula." ed254_d_datacad = '$this->ed254_d_datacad' ";
       $virgula = ",";
       if(trim($this->ed254_d_datacad) == null ){
         $this->erro_sql = " Campo Data do Cadastro nao Informado.";
         $this->erro_campo = "ed254_d_datacad_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"])){
         $sql  .= $virgula." ed254_d_datacad = null ";
         $virgula = ",";
         if(trim($this->ed254_d_datacad) == null ){
           $this->erro_sql = " Campo Data do Cadastro nao Informado.";
           $this->erro_campo = "ed254_d_datacad_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed254_i_codigo!=null){
       $sql .= " ed254_i_codigo = $this->ed254_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed254_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12514,'$this->ed254_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2183,12514,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_codigo'))."','$this->ed254_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"]))
           $resac = db_query("insert into db_acount values($acount,2183,12515,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_rechumano'))."','$this->ed254_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,2183,12516,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_escola'))."','$this->ed254_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"]))
           $resac = db_query("insert into db_acount values($acount,2183,12517,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_turno'))."','$this->ed254_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"]))
           $resac = db_query("insert into db_acount values($acount,2183,12551,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_atolegal'))."','$this->ed254_i_atolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_email"]))
           $resac = db_query("insert into db_acount values($acount,2183,12557,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_c_email'))."','$this->ed254_c_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini"]))
           $resac = db_query("insert into db_acount values($acount,2183,12553,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_dataini'))."','$this->ed254_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim"]))
           $resac = db_query("insert into db_acount values($acount,2183,12554,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_datafim'))."','$this->ed254_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2183,12555,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_c_tipo'))."','$this->ed254_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2183,12552,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_usuario'))."','$this->ed254_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad"]))
           $resac = db_query("insert into db_acount values($acount,2183,12556,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_datacad'))."','$this->ed254_d_datacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores da Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diretores da Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed254_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed254_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12514,'$ed254_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2183,12514,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12515,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12516,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12517,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12551,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12557,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12553,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12554,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12555,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12552,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12556,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escoladiretor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed254_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed254_i_codigo = $ed254_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores da Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed254_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diretores da Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed254_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escoladiretor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed254_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from escoladiretor ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = escoladiretor.ed254_i_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escoladiretor.ed254_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      left  join atolegal  on  atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = escoladiretor.ed254_i_turno";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rhpessoalmov  on rhpessoalmov.rh02_anousu  = $ano
                                           and rhpessoalmov.rh02_mesusu  = $mes
                                           and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                           and rhpessoalmov.rh02_instit  = $instit";
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";
     $sql2 = "";
     if($dbwhere==""){
       if($ed254_i_codigo!=null ){
         $sql2 .= " where escoladiretor.ed254_i_codigo = $ed254_i_codigo ";
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
   function sql_query_file ( $ed254_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from escoladiretor ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed254_i_codigo!=null ){
         $sql2 .= " where escoladiretor.ed254_i_codigo = $ed254_i_codigo ";
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
  
  function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $instit = db_getsession("DB_instit");
    $ano    = db_anofolha();
    $mes    = db_mesfolha();
    $sSql  .= " from escoladiretor ";
    $sSql  .= "      left  join atolegal  on  atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal";
    $sSql  .= "      left  join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";
    $sSql  .= "      inner join turno  on  turno.ed15_i_codigo = escoladiretor.ed254_i_turno";
    $sSql  .= "      inner join rechumano  on  rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano";
    $sSql  .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sSql  .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sSql  .= "      left join rhpessoalmov on rh02_anousu  = $ano";
    $sSql  .= "                                and rh02_mesusu  = $mes";
    $sSql  .= "                                and rh02_regist  = rh01_regist";
    $sSql  .= "                                and rh02_instit  = $instit";
    $sSql  .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao"; 
    $sSql  .= "                              and rh37_instit  = rh02_instit";
    $sSql  .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where escoladiretor.ed254_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  
  
  function sql_query_resultadofinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    
    $iInst  = db_getsession("DB_instit");
    $iAno   = db_anofolha();
    $iMes   = db_mesfolha();
    
    $sSql  .= " FROM escoladiretor ";
    $sSql  .= "   INNER JOIN turno ON turno.ed15_i_codigo = escoladiretor.ed254_i_turno ";
    $sSql  .= "   LEFT JOIN atolegal ON atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal ";
    $sSql  .= "   LEFT JOIN tipoato ON tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato ";
    $sSql  .= "    ";
    $sSql  .= "   INNER JOIN rechumano ON rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano ";
    $sSql  .= "    ";
    $sSql  .= "   LEFT JOIN rechumanopessoal ON rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN rhpessoal ON rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql  .= "   LEFT JOIN cgm AS cgmrh ON cgmrh.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sSql  .= "   LEFT JOIN rechumanoescola ON rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN rechumanoativ ON rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo ";
    $sSql  .= "   LEFT JOIN atividaderh ON atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade ";
    $sSql  .= "    ";
    $sSql  .= "   LEFT JOIN rhpessoalmov ON rh02_anousu = ".$iAno." ";
    $sSql  .= "                          AND rh02_mesusu = ".$iMes." ";
    $sSql  .= "                          AND rh02_regist = rh01_regist ";
    $sSql  .= "                          AND rh02_instit = ".$iInst." ";
    $sSql  .= "   LEFT JOIN rhfuncao ON rhfuncao.rh37_funcao = rhpessoal.rh01_funcao ";
    $sSql  .= "                      AND rh37_instit  = rh02_instit ";
    $sSql  .= "   LEFT JOIN rechumanocgm ON rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN cgm AS cgmcgm ON cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm ";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where escoladiretor.ed254_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  
}
?>