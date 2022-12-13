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
//CLASSE DA ENTIDADE transfescolafora
class cl_transfescolafora {
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
   var $ed104_i_codigo = 0;
   var $ed104_i_aluno = 0;
   var $ed104_i_escolaorigem = 0;
   var $ed104_i_escoladestino = 0;
   var $ed104_i_usuario = 0;
   var $ed104_d_data_dia = null;
   var $ed104_d_data_mes = null;
   var $ed104_d_data_ano = null;
   var $ed104_d_data = null;
   var $ed104_t_obs = null;
   var $ed104_i_matricula = 0;
   var $ed104_c_situacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed104_i_codigo = int8 = Código
                 ed104_i_aluno = int8 = Aluno
                 ed104_i_escolaorigem = int8 = Escola Origem
                 ed104_i_escoladestino = int8 = Escola Destino
                 ed104_i_usuario = int8 = Usuário
                 ed104_d_data = date = Data
                 ed104_t_obs = text = Observações
                 ed104_i_matricula = int8 = Matrícula
                 ed104_c_situacao = char(1) = Situação
                 ";
   //funcao construtor da classe
   function cl_transfescolafora() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transfescolafora");
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
       $this->ed104_i_codigo = ($this->ed104_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_codigo"]:$this->ed104_i_codigo);
       $this->ed104_i_aluno = ($this->ed104_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_aluno"]:$this->ed104_i_aluno);
       $this->ed104_i_escolaorigem = ($this->ed104_i_escolaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_escolaorigem"]:$this->ed104_i_escolaorigem);
       $this->ed104_i_escoladestino = ($this->ed104_i_escoladestino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_escoladestino"]:$this->ed104_i_escoladestino);
       $this->ed104_i_usuario = ($this->ed104_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_usuario"]:$this->ed104_i_usuario);
       if($this->ed104_d_data == ""){
         $this->ed104_d_data_dia = ($this->ed104_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_d_data_dia"]:$this->ed104_d_data_dia);
         $this->ed104_d_data_mes = ($this->ed104_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_d_data_mes"]:$this->ed104_d_data_mes);
         $this->ed104_d_data_ano = ($this->ed104_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_d_data_ano"]:$this->ed104_d_data_ano);
         if($this->ed104_d_data_dia != ""){
            $this->ed104_d_data = $this->ed104_d_data_ano."-".$this->ed104_d_data_mes."-".$this->ed104_d_data_dia;
         }
       }
       $this->ed104_t_obs = ($this->ed104_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_t_obs"]:$this->ed104_t_obs);
       $this->ed104_i_matricula = ($this->ed104_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_matricula"]:$this->ed104_i_matricula);
       $this->ed104_c_situacao = ($this->ed104_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_c_situacao"]:$this->ed104_c_situacao);
     }else{
       $this->ed104_i_codigo = ($this->ed104_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed104_i_codigo"]:$this->ed104_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed104_i_codigo){
      $this->atualizacampos();
     if($this->ed104_i_aluno == null ){
       $this->erro_sql = " Campo Aluno não informado.";
       $this->erro_campo = "ed104_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_i_escolaorigem == null ){
       $this->erro_sql = " Campo Escola Origem não informado.";
       $this->erro_campo = "ed104_i_escolaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_i_escoladestino == null ){
       $this->erro_sql = " Campo Escola Destino não informado.";
       $this->erro_campo = "ed104_i_escoladestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_i_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ed104_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_d_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ed104_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_i_matricula == null ){
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "ed104_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed104_c_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "ed104_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed104_i_codigo == "" || $ed104_i_codigo == null ){
       $result = db_query("select nextval('transfescolafora_ed104_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transfescolafora_ed104_i_codigo_seq do campo: ed104_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed104_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from transfescolafora_ed104_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed104_i_codigo)){
         $this->erro_sql = " Campo ed104_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed104_i_codigo = $ed104_i_codigo;
       }
     }
     if(($this->ed104_i_codigo == null) || ($this->ed104_i_codigo == "") ){
       $this->erro_sql = " Campo ed104_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transfescolafora(
                                       ed104_i_codigo
                                      ,ed104_i_aluno
                                      ,ed104_i_escolaorigem
                                      ,ed104_i_escoladestino
                                      ,ed104_i_usuario
                                      ,ed104_d_data
                                      ,ed104_t_obs
                                      ,ed104_i_matricula
                                      ,ed104_c_situacao
                       )
                values (
                                $this->ed104_i_codigo
                               ,$this->ed104_i_aluno
                               ,$this->ed104_i_escolaorigem
                               ,$this->ed104_i_escoladestino
                               ,$this->ed104_i_usuario
                               ,".($this->ed104_d_data == "null" || $this->ed104_d_data == ""?"null":"'".$this->ed104_d_data."'")."
                               ,'$this->ed104_t_obs'
                               ,$this->ed104_i_matricula
                               ,'$this->ed104_c_situacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferências ($this->ed104_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferências já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferências ($this->ed104_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed104_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed104_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009051,'$this->ed104_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010163,1009051,'','".AddSlashes(pg_result($resaco,0,'ed104_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009052,'','".AddSlashes(pg_result($resaco,0,'ed104_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009053,'','".AddSlashes(pg_result($resaco,0,'ed104_i_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009054,'','".AddSlashes(pg_result($resaco,0,'ed104_i_escoladestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009055,'','".AddSlashes(pg_result($resaco,0,'ed104_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009056,'','".AddSlashes(pg_result($resaco,0,'ed104_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,1009057,'','".AddSlashes(pg_result($resaco,0,'ed104_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,14553,'','".AddSlashes(pg_result($resaco,0,'ed104_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010163,18325,'','".AddSlashes(pg_result($resaco,0,'ed104_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed104_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update transfescolafora set ";
     $virgula = "";
     if(trim($this->ed104_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_codigo"])){
       $sql  .= $virgula." ed104_i_codigo = $this->ed104_i_codigo ";
       $virgula = ",";
       if(trim($this->ed104_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed104_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_aluno"])){
       $sql  .= $virgula." ed104_i_aluno = $this->ed104_i_aluno ";
       $virgula = ",";
       if(trim($this->ed104_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno não informado.";
         $this->erro_campo = "ed104_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_i_escolaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_escolaorigem"])){
       $sql  .= $virgula." ed104_i_escolaorigem = $this->ed104_i_escolaorigem ";
       $virgula = ",";
       if(trim($this->ed104_i_escolaorigem) == null ){
         $this->erro_sql = " Campo Escola Origem não informado.";
         $this->erro_campo = "ed104_i_escolaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_i_escoladestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_escoladestino"])){
       $sql  .= $virgula." ed104_i_escoladestino = $this->ed104_i_escoladestino ";
       $virgula = ",";
       if(trim($this->ed104_i_escoladestino) == null ){
         $this->erro_sql = " Campo Escola Destino não informado.";
         $this->erro_campo = "ed104_i_escoladestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_usuario"])){
       $sql  .= $virgula." ed104_i_usuario = $this->ed104_i_usuario ";
       $virgula = ",";
       if(trim($this->ed104_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ed104_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed104_d_data_dia"] !="") ){
       $sql  .= $virgula." ed104_d_data = '$this->ed104_d_data' ";
       $virgula = ",";
       if(trim($this->ed104_d_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ed104_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed104_d_data_dia"])){
         $sql  .= $virgula." ed104_d_data = null ";
         $virgula = ",";
         if(trim($this->ed104_d_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ed104_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed104_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_t_obs"])){
       $sql  .= $virgula." ed104_t_obs = '$this->ed104_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed104_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_matricula"])){
       $sql  .= $virgula." ed104_i_matricula = $this->ed104_i_matricula ";
       $virgula = ",";
       if(trim($this->ed104_i_matricula) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "ed104_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed104_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed104_c_situacao"])){
       $sql  .= $virgula." ed104_c_situacao = '$this->ed104_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed104_c_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "ed104_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed104_i_codigo!=null){
       $sql .= " ed104_i_codigo = $this->ed104_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed104_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009051,'$this->ed104_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_codigo"]) || $this->ed104_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009051,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_codigo'))."','$this->ed104_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_aluno"]) || $this->ed104_i_aluno != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009052,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_aluno'))."','$this->ed104_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_escolaorigem"]) || $this->ed104_i_escolaorigem != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009053,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_escolaorigem'))."','$this->ed104_i_escolaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_escoladestino"]) || $this->ed104_i_escoladestino != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009054,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_escoladestino'))."','$this->ed104_i_escoladestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_usuario"]) || $this->ed104_i_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009055,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_usuario'))."','$this->ed104_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_d_data"]) || $this->ed104_d_data != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009056,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_d_data'))."','$this->ed104_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_t_obs"]) || $this->ed104_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010163,1009057,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_t_obs'))."','$this->ed104_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_i_matricula"]) || $this->ed104_i_matricula != "")
             $resac = db_query("insert into db_acount values($acount,1010163,14553,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_i_matricula'))."','$this->ed104_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed104_c_situacao"]) || $this->ed104_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010163,18325,'".AddSlashes(pg_result($resaco,$conresaco,'ed104_c_situacao'))."','$this->ed104_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferências nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed104_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Transferências nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed104_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed104_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed104_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed104_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009051,'$ed104_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009051,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009052,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009053,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009054,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_escoladestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009055,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009056,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,1009057,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,14553,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010163,18325,'','".AddSlashes(pg_result($resaco,$iresaco,'ed104_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from transfescolafora
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed104_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed104_i_codigo = $ed104_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferências nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed104_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Transferências nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed104_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed104_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:transfescolafora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed104_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= " from transfescolafora ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transfescolafora.ed104_i_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = transfescolafora.ed104_i_escolaorigem";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = transfescolafora.ed104_i_aluno";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = aluno.ed47_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = aluno.ed47_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = aluno.ed47_i_censoufcert";
     $sql .= "      left  join censouf as censoufend on  censoufend.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = aluno.ed47_i_censomunicnat";
     $sql .= "      left  join censomunic as censomuniccert on  censomuniccert.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censomunic as censomunicend on  censomunicend.ed261_i_codigo = aluno.ed47_i_censomunicend";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = transfescolafora.ed104_i_matricula";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed104_i_codigo)) {
         $sql2 .= " where transfescolafora.ed104_i_codigo = $ed104_i_codigo ";
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
   public function sql_query_file ($ed104_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from transfescolafora ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed104_i_codigo)){
         $sql2 .= " where transfescolafora.ed104_i_codigo = $ed104_i_codigo ";
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

   function sql_query_alunotransf($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from transfescolafora ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed104_i_aluno ";
    $sSql .= "      inner join escolaproc on ed82_i_codigo = ed104_i_escoladestino";
    $sSql .= "      inner join alunocurso on ed56_i_aluno = ed47_i_codigo ";
    $sSql .= "      inner join matricula  on  matricula.ed60_i_codigo = transfescolafora.ed104_i_matricula";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where transfescolafora.ed104_i_codigo = $iCodigo ";
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
   function sql_query_transferidofora($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

      $sSql  .= ' FROM transfescolafora ';
      $sSql  .= '      inner join escola  on  escola.ed18_i_codigo = transfescolafora.ed104_i_escolaorigem ';
      $sSql  .= '      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf ';
      $sSql  .= '      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic ';
      $sSql  .= '      inner join aluno  on  aluno.ed47_i_codigo = transfescolafora.ed104_i_aluno ';
      $sSql  .= '      inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ';
      $sSql  .= '      left join censouf  as dd on dd.ed260_i_codigo = escolaproc.ed82_i_censouf ';
      $sSql  .= '      left join censomunic as ss on ss.ed261_i_codigo = escolaproc.ed82_i_censomunic ';
      $sSql2  = " ";

      if ($sDbWhere == '') {

        if ($iCodigo != null ){
          $sSql2 .= " transfescolafora.ed104_i_codigo = $iCodigo ";
        }

      } else if ($sDbWhere != '') {
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

  function sql_query_matriculaserie($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from transfescolafora ";
    $sSql .= "      inner join aluno          on ed47_i_codigo                    = ed104_i_aluno ";
    $sSql .= "      inner join matricula      on matricula.ed60_i_codigo          = transfescolafora.ed104_i_matricula";
    $sSql .= "      left  join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where transfescolafora.ed104_i_codigo = $iCodigo ";
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