<?php
/**
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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptuender
class cl_iptuender {
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
   var $j43_matric = 0;
   var $j43_dest = null;
   var $j43_ender = null;
   var $j43_numimo = 0;
   var $j43_comple = null;
   var $j43_bairro = null;
   var $j43_munic = null;
   var $j43_uf = null;
   var $j43_cep = null;
   var $j43_cxpost = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j43_matric = int4 = Matricula
                 j43_dest = varchar(40) = Nome Destinatário
                 j43_ender = varchar(40) = Logradouro
                 j43_numimo = int4 = Número
                 j43_comple = char(20) = complemento
                 j43_bairro = varchar(40) = Bairro
                 j43_munic = varchar(20) = Cidade
                 j43_uf = char(2) = UF
                 j43_cep = char(8) = Cep
                 j43_cxpost = int4 = caixa postal
                 ";
   //funcao construtor da classe
   function cl_iptuender() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuender");
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
       $this->j43_matric = ($this->j43_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_matric"]:$this->j43_matric);
       $this->j43_dest = ($this->j43_dest == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_dest"]:$this->j43_dest);
       $this->j43_ender = ($this->j43_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_ender"]:$this->j43_ender);
       $this->j43_numimo = ($this->j43_numimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_numimo"]:$this->j43_numimo);
       $this->j43_comple = ($this->j43_comple == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_comple"]:$this->j43_comple);
       $this->j43_bairro = ($this->j43_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_bairro"]:$this->j43_bairro);
       $this->j43_munic = ($this->j43_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_munic"]:$this->j43_munic);
       $this->j43_uf = ($this->j43_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_uf"]:$this->j43_uf);
       $this->j43_cep = ($this->j43_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_cep"]:$this->j43_cep);
       $this->j43_cxpost = ($this->j43_cxpost == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_cxpost"]:$this->j43_cxpost);
     }else{
       $this->j43_matric = ($this->j43_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j43_matric"]:$this->j43_matric);
     }
   }
   // funcao para Inclusão
   function incluir ($j43_matric){

    $this->atualizacampos();

     $this->j43_matric = $j43_matric;
     if(($this->j43_matric == null) || ($this->j43_matric == "") ){
       $this->erro_sql = " Campo Matricula é de preenchimento obrigatório!";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_cep == null ){
       $this->erro_sql = " Campo Cep é de preenchimento obrigatório!";
       $this->erro_campo = "j43_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if(strlen($this->j43_cep) < 8){
       $this->erro_sql = " Campo Cep deve possuir oito (8) digitos.";
       $this->erro_campo = "j43_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_uf == null ){
       $this->erro_sql = " Campo Estado é de preenchimento obrigatório!";
       $this->erro_campo = "j43_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if(strlen($this->j43_uf) < 2){
       $this->erro_sql = " Campo Estado deve possuir dois (2) digitos.";
       $this->erro_campo = "j43_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_munic == null ){
       $this->erro_sql = " Campo Município é de preenchimento obrigatório!";
       $this->erro_campo = "j43_munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_bairro == null ){
       $this->erro_sql = " Campo Bairro é de preenchimento obrigatório!";
       $this->erro_campo = "j43_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_ender == null ){
       $this->erro_sql = " Campo Logradouro é de preenchimento obrigatório!";
       $this->erro_campo = "j43_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_numimo == null ){
       $this->erro_sql = " Campo Número é de preenchimento obrigatório!";
       $this->erro_campo = "j43_numimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->j43_cxpost == null ){
       $this->j43_cxpost = "0";
     }

     $sql = "insert into iptuender(
                                       j43_matric
                                      ,j43_dest
                                      ,j43_ender
                                      ,j43_numimo
                                      ,j43_comple
                                      ,j43_bairro
                                      ,j43_munic
                                      ,j43_uf
                                      ,j43_cep
                                      ,j43_cxpost
                       )
                values (
                                $this->j43_matric
                               ,'$this->j43_dest'
                               ,'$this->j43_ender'
                               ,$this->j43_numimo
                               ,'$this->j43_comple'
                               ,'$this->j43_bairro'
                               ,'$this->j43_munic'
                               ,'$this->j43_uf'
                               ,'$this->j43_cep'
                               ,$this->j43_cxpost
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j43_matric) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j43_matric) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j43_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j43_matric  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,162,'$this->j43_matric','I')");
         $resac = db_query("insert into db_acount values($acount,32,162,'','".AddSlashes(pg_result($resaco,0,'j43_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,167,'','".AddSlashes(pg_result($resaco,0,'j43_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,164,'','".AddSlashes(pg_result($resaco,0,'j43_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,2358,'','".AddSlashes(pg_result($resaco,0,'j43_numimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,2360,'','".AddSlashes(pg_result($resaco,0,'j43_comple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,3587,'','".AddSlashes(pg_result($resaco,0,'j43_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,163,'','".AddSlashes(pg_result($resaco,0,'j43_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,166,'','".AddSlashes(pg_result($resaco,0,'j43_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,165,'','".AddSlashes(pg_result($resaco,0,'j43_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,32,2359,'','".AddSlashes(pg_result($resaco,0,'j43_cxpost'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($j43_matric=null) {
      $this->atualizacampos();
     $sql = " update iptuender set ";
     $virgula = "";
     if(trim($this->j43_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_matric"])){
       $sql  .= $virgula." j43_matric = $this->j43_matric ";
       $virgula = ",";
       if(trim($this->j43_matric) == null ){
         $this->erro_sql = " Campo Matricula é de preenchimento obrigatório!";
         $this->erro_campo = "j43_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_cep"])){
       $sql  .= $virgula." j43_cep = '$this->j43_cep' ";
       $virgula = ",";
       if(trim($this->j43_cep) == null ){
         $this->erro_sql = " Campo Cep é de preenchimento obrigatório!";
         $this->erro_campo = "j43_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if(strlen($this->j43_cep) < 8){
         $this->erro_sql = " Campo Cep deve possuir oito (8) digitos.";
         $this->erro_campo = "j43_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_uf"])){
       $sql  .= $virgula." j43_uf = '$this->j43_uf' ";
       $virgula = ",";
       if(trim($this->j43_uf) == null ){
         $this->erro_sql = " Campo Estado é de preenchimento obrigatório!";
         $this->erro_campo = "j43_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if(strlen($this->j43_uf) < 2){
         $this->erro_sql = " Campo Estado deve possuir dois (2) digitos.";
         $this->erro_campo = "j43_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_munic"])){
       $sql  .= $virgula." j43_munic = '$this->j43_munic' ";
       $virgula = ",";
       if(trim($this->j43_munic) == null ){
         $this->erro_sql = " Campo Município é de preenchimento obrigatório!";
         $this->erro_campo = "j43_munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_bairro"])){
       $sql  .= $virgula." j43_bairro = '$this->j43_bairro' ";
       $virgula = ",";
       if(trim($this->j43_bairro) == null ){
         $this->erro_sql = " Campo Bairro é de preenchimento obrigatório!";
         $this->erro_campo = "j43_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_ender"])){
       $sql  .= $virgula." j43_ender = '$this->j43_ender' ";
       $virgula = ",";
       if(trim($this->j43_ender) == null ){
         $this->erro_sql = " Campo Logradouro é de preenchimento obrigatório!";
         $this->erro_campo = "j43_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_numimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_numimo"])){
       $sql  .= $virgula." j43_numimo = $this->j43_numimo ";
       $virgula = ",";
       if(trim($this->j43_numimo) == null ){
         $this->erro_sql = " Campo Número é de preenchimento obrigatório!";
         $this->erro_campo = "j43_numimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j43_dest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_dest"])){
       $sql  .= $virgula." j43_dest = '$this->j43_dest' ";
       $virgula = ",";
     }
     if(trim($this->j43_comple)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_comple"])){
       $sql  .= $virgula." j43_comple = '$this->j43_comple' ";
       $virgula = ",";
     }
     if(trim($this->j43_cxpost)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j43_cxpost"])){
        if(trim($this->j43_cxpost)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j43_cxpost"])){
           $this->j43_cxpost = "0" ;
        }
       $sql  .= $virgula." j43_cxpost = $this->j43_cxpost ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j43_matric!=null){
       $sql .= " j43_matric = $this->j43_matric";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j43_matric));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,162,'$this->j43_matric','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_matric"]) || $this->j43_matric != "")
             $resac = db_query("insert into db_acount values($acount,32,162,'".AddSlashes(pg_result($resaco,$conresaco,'j43_matric'))."','$this->j43_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_dest"]) || $this->j43_dest != "")
             $resac = db_query("insert into db_acount values($acount,32,167,'".AddSlashes(pg_result($resaco,$conresaco,'j43_dest'))."','$this->j43_dest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_ender"]) || $this->j43_ender != "")
             $resac = db_query("insert into db_acount values($acount,32,164,'".AddSlashes(pg_result($resaco,$conresaco,'j43_ender'))."','$this->j43_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_numimo"]) || $this->j43_numimo != "")
             $resac = db_query("insert into db_acount values($acount,32,2358,'".AddSlashes(pg_result($resaco,$conresaco,'j43_numimo'))."','$this->j43_numimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_comple"]) || $this->j43_comple != "")
             $resac = db_query("insert into db_acount values($acount,32,2360,'".AddSlashes(pg_result($resaco,$conresaco,'j43_comple'))."','$this->j43_comple',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_bairro"]) || $this->j43_bairro != "")
             $resac = db_query("insert into db_acount values($acount,32,3587,'".AddSlashes(pg_result($resaco,$conresaco,'j43_bairro'))."','$this->j43_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_munic"]) || $this->j43_munic != "")
             $resac = db_query("insert into db_acount values($acount,32,163,'".AddSlashes(pg_result($resaco,$conresaco,'j43_munic'))."','$this->j43_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_uf"]) || $this->j43_uf != "")
             $resac = db_query("insert into db_acount values($acount,32,166,'".AddSlashes(pg_result($resaco,$conresaco,'j43_uf'))."','$this->j43_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_cep"]) || $this->j43_cep != "")
             $resac = db_query("insert into db_acount values($acount,32,165,'".AddSlashes(pg_result($resaco,$conresaco,'j43_cep'))."','$this->j43_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j43_cxpost"]) || $this->j43_cxpost != "")
             $resac = db_query("insert into db_acount values($acount,32,2359,'".AddSlashes(pg_result($resaco,$conresaco,'j43_cxpost'))."','$this->j43_cxpost',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j43_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j43_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j43_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($j43_matric=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j43_matric));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,162,'$j43_matric','E')");
           $resac  = db_query("insert into db_acount values($acount,32,162,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,167,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,164,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,2358,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_numimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,2360,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_comple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,3587,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,163,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,166,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,165,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,32,2359,'','".AddSlashes(pg_result($resaco,$iresaco,'j43_cxpost'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from iptuender
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j43_matric)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j43_matric = $j43_matric ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j43_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j43_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j43_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:iptuender";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($j43_matric = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from iptuender ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuender.j43_matric";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j43_matric)) {
         $sql2 .= " where iptuender.j43_matric = $j43_matric ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($j43_matric = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from iptuender ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j43_matric)){
         $sql2 .= " where iptuender.j43_matric = $j43_matric ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_endereco ( $j43_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from iptubase ";
     $sql .= "      left join iptuender on iptuender.j43_matric = iptubase.j01_matric";
     $sql .= "      inner join cgm      on cgm.z01_numcgm = iptubase.j01_numcgm";

     $sql2 = "";

     $where_ender ="
(length(trim(iptuender.j43_ender))  <> 0 or (iptuender.j43_cxpost = 0 and length(trim(iptuender.j43_cxpost)) = 0)  )
     or   ( length(trim(cgm.z01_ender)) = 0 and (cgm.z01_cxpostal = 0  and length(trim(cgm.z01_cxpostal)) = 0))
     ";
 $where_ender ="

            (length(trim(j43_ender)) <> 0 or (j43_cxpost > 0 and length(trim(j43_cxpost)) > 0))
           or ( length(trim(z01_ender)) > 0 or (z01_cxpostal > 0 and length(trim(z01_cxpostal)) > 0))
           ";

     if($dbwhere==""){
       if($j43_matric!=null ){
         $sql2 .= " iptubase.j01_matric = $j43_matric";
       }
     }else if($dbwhere != ""){
//     $sql2 = " where $dbwhere and $where_ender";
       $sql2 = " $where_ender";
     }

     $sql = "select * from ($sql and $sql2) as x where $where_ender";
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
   /**
   * Sql com todas as matriculas o mesmo grupo de endereço
   *
   * @param integer $iGrupoEndereco - pk da tabela iptuendergrupo
   * @param string $sCampos
   * @param string $sWhere
   * @access public
   * @return string
   */
  function sql_queryMatriculasAgrupadas($iGrupoEndereco, $sCampos = '*', $sWhere = null) {

    $sWhereQuery = 'where j43_iptuendergrupo = '.$iGrupoEndereco;

    if ($sWhere != null) {
     $sWhereQuery = $sWhereQuery.' and '.$sWhere;
    }

    $sSql = "select {$sCampos} from iptuender {$sWhereQuery}";

    return $sSql;
  }
}
