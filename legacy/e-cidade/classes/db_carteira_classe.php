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

//MODULO: biblioteca
//CLASSE DA ENTIDADE carteira
class cl_carteira {
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
   var $bi16_codigo = 0;
   var $bi16_leitor = 0;
   var $bi16_leitorcategoria = 0;
   var $bi16_usuario = 0;
   var $bi16_inclusao_dia = null;
   var $bi16_inclusao_mes = null;
   var $bi16_inclusao_ano = null;
   var $bi16_inclusao = null;
   var $bi16_validade_dia = null;
   var $bi16_validade_mes = null;
   var $bi16_validade_ano = null;
   var $bi16_validade = null;
   var $bi16_valida = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi16_codigo = int8 = Código
                 bi16_leitor = int8 = Leitor
                 bi16_leitorcategoria = int8 = Categoria
                 bi16_usuario = int8 = Usuário
                 bi16_inclusao = date = data de Inclusão
                 bi16_validade = date = Validade
                 bi16_valida = char(1) = Válida
                 ";
   //funcao construtor da classe
   function cl_carteira() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carteira");
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
       $this->bi16_codigo = ($this->bi16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_codigo"]:$this->bi16_codigo);
       $this->bi16_leitor = ($this->bi16_leitor == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_leitor"]:$this->bi16_leitor);
       $this->bi16_leitorcategoria = ($this->bi16_leitorcategoria == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_leitorcategoria"]:$this->bi16_leitorcategoria);
       $this->bi16_usuario = ($this->bi16_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_usuario"]:$this->bi16_usuario);
       if($this->bi16_inclusao == ""){
         $this->bi16_inclusao_dia = ($this->bi16_inclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_dia"]:$this->bi16_inclusao_dia);
         $this->bi16_inclusao_mes = ($this->bi16_inclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_mes"]:$this->bi16_inclusao_mes);
         $this->bi16_inclusao_ano = ($this->bi16_inclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_ano"]:$this->bi16_inclusao_ano);
         if($this->bi16_inclusao_dia != ""){
            $this->bi16_inclusao = $this->bi16_inclusao_ano."-".$this->bi16_inclusao_mes."-".$this->bi16_inclusao_dia;
         }
       }
       if($this->bi16_validade == ""){
         $this->bi16_validade_dia = ($this->bi16_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_validade_dia"]:$this->bi16_validade_dia);
         $this->bi16_validade_mes = ($this->bi16_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_validade_mes"]:$this->bi16_validade_mes);
         $this->bi16_validade_ano = ($this->bi16_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_validade_ano"]:$this->bi16_validade_ano);
         if($this->bi16_validade_dia != ""){
            $this->bi16_validade = $this->bi16_validade_ano."-".$this->bi16_validade_mes."-".$this->bi16_validade_dia;
         }
       }
       $this->bi16_valida = ($this->bi16_valida == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_valida"]:$this->bi16_valida);
     }else{
       $this->bi16_codigo = ($this->bi16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi16_codigo"]:$this->bi16_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi16_codigo){
      $this->atualizacampos();
     if($this->bi16_leitor == null ){
       $this->erro_sql = " Campo Leitor nao Informado.";
       $this->erro_campo = "bi16_leitor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi16_leitorcategoria == null ){
       $this->erro_sql = " Campo Categoria nao Informado.";
       $this->erro_campo = "bi16_leitorcategoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi16_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "bi16_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi16_inclusao == null ){
       $this->erro_sql = " Campo data de Inclusão nao Informado.";
       $this->erro_campo = "bi16_inclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi16_validade == null ){
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "bi16_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi16_valida == null ){
       $this->erro_sql = " Campo Válida nao Informado.";
       $this->erro_campo = "bi16_valida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi16_codigo == "" || $bi16_codigo == null ){
       $result = db_query("select nextval('carteira_bi16_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: carteira_bi16_codigo_seq do campo: bi16_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi16_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from carteira_bi16_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi16_codigo)){
         $this->erro_sql = " Campo bi16_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi16_codigo = $bi16_codigo;
       }
     }
     if(($this->bi16_codigo == null) || ($this->bi16_codigo == "") ){
       $this->erro_sql = " Campo bi16_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carteira(
                                       bi16_codigo
                                      ,bi16_leitor
                                      ,bi16_leitorcategoria
                                      ,bi16_usuario
                                      ,bi16_inclusao
                                      ,bi16_validade
                                      ,bi16_valida
                       )
                values (
                                $this->bi16_codigo
                               ,$this->bi16_leitor
                               ,$this->bi16_leitorcategoria
                               ,$this->bi16_usuario
                               ,".($this->bi16_inclusao == "null" || $this->bi16_inclusao == ""?"null":"'".$this->bi16_inclusao."'")."
                               ,".($this->bi16_validade == "null" || $this->bi16_validade == ""?"null":"'".$this->bi16_validade."'")."
                               ,'$this->bi16_valida'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Carteira ($this->bi16_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Carteira já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Carteira ($this->bi16_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi16_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi16_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008146,'$this->bi16_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008021,1008146,'','".AddSlashes(pg_result($resaco,0,'bi16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008149,'','".AddSlashes(pg_result($resaco,0,'bi16_leitor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008933,'','".AddSlashes(pg_result($resaco,0,'bi16_leitorcategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008932,'','".AddSlashes(pg_result($resaco,0,'bi16_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008147,'','".AddSlashes(pg_result($resaco,0,'bi16_inclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008148,'','".AddSlashes(pg_result($resaco,0,'bi16_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008021,1008934,'','".AddSlashes(pg_result($resaco,0,'bi16_valida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($bi16_codigo=null) {
      $this->atualizacampos();
     $sql = " update carteira set ";
     $virgula = "";
     if(trim($this->bi16_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_codigo"])){
       $sql  .= $virgula." bi16_codigo = $this->bi16_codigo ";
       $virgula = ",";
       if(trim($this->bi16_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi16_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi16_leitor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_leitor"])){
       $sql  .= $virgula." bi16_leitor = $this->bi16_leitor ";
       $virgula = ",";
       if(trim($this->bi16_leitor) == null ){
         $this->erro_sql = " Campo Leitor nao Informado.";
         $this->erro_campo = "bi16_leitor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi16_leitorcategoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_leitorcategoria"])){
       $sql  .= $virgula." bi16_leitorcategoria = $this->bi16_leitorcategoria ";
       $virgula = ",";
       if(trim($this->bi16_leitorcategoria) == null ){
         $this->erro_sql = " Campo Categoria nao Informado.";
         $this->erro_campo = "bi16_leitorcategoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi16_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_usuario"])){
       $sql  .= $virgula." bi16_usuario = $this->bi16_usuario ";
       $virgula = ",";
       if(trim($this->bi16_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "bi16_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi16_inclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_dia"] !="") ){
       $sql  .= $virgula." bi16_inclusao = '$this->bi16_inclusao' ";
       $virgula = ",";
       if(trim($this->bi16_inclusao) == null ){
         $this->erro_sql = " Campo data de Inclusão nao Informado.";
         $this->erro_campo = "bi16_inclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_inclusao_dia"])){
         $sql  .= $virgula." bi16_inclusao = null ";
         $virgula = ",";
         if(trim($this->bi16_inclusao) == null ){
           $this->erro_sql = " Campo data de Inclusão nao Informado.";
           $this->erro_campo = "bi16_inclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi16_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi16_validade_dia"] !="") ){
       $sql  .= $virgula." bi16_validade = '$this->bi16_validade' ";
       $virgula = ",";
       if(trim($this->bi16_validade) == null ){
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "bi16_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_validade_dia"])){
         $sql  .= $virgula." bi16_validade = null ";
         $virgula = ",";
         if(trim($this->bi16_validade) == null ){
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "bi16_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi16_valida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi16_valida"])){
       $sql  .= $virgula." bi16_valida = '$this->bi16_valida' ";
       $virgula = ",";
       if(trim($this->bi16_valida) == null ){
         $this->erro_sql = " Campo Válida nao Informado.";
         $this->erro_campo = "bi16_valida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi16_codigo!=null){
       $sql .= " bi16_codigo = $this->bi16_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi16_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008146,'$this->bi16_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008146,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_codigo'))."','$this->bi16_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_leitor"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008149,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_leitor'))."','$this->bi16_leitor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_leitorcategoria"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008933,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_leitorcategoria'))."','$this->bi16_leitorcategoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008932,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_usuario'))."','$this->bi16_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_inclusao"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008147,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_inclusao'))."','$this->bi16_inclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_validade"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008148,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_validade'))."','$this->bi16_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi16_valida"]))
           $resac = db_query("insert into db_acount values($acount,1008021,1008934,'".AddSlashes(pg_result($resaco,$conresaco,'bi16_valida'))."','$this->bi16_valida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Carteira nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi16_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Carteira nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($bi16_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi16_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008146,'$bi16_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008021,1008146,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008149,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_leitor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008933,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_leitorcategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008932,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008147,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_inclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008148,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008021,1008934,'','".AddSlashes(pg_result($resaco,$iresaco,'bi16_valida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carteira
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi16_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi16_codigo = $bi16_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Carteira nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi16_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Carteira nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi16_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:carteira";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi16_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from carteira ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = carteira.bi16_usuario";
     $sql .= "      inner join leitorcategoria  on  leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = leitorcategoria.bi07_biblioteca";
     $sql .= "      inner join leitor  on  leitor.bi10_codigo = carteira.bi16_leitor";
     $sql .= "      left join leitoraluno on leitoraluno.bi11_leitor = leitor.bi10_codigo";
     $sql .= "      left join aluno on aluno.ed47_i_codigo = leitoraluno.bi11_aluno";
     $sql .= "      left join alunocurso on alunocurso.ed56_i_aluno = ed47_i_codigo";
     $sql .= "      left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola";
     $sql .= "      left join leitorfunc on leitorfunc.bi12_leitor = leitor.bi10_codigo";
     $sql .= "      left join rechumano on rechumano.ed20_i_codigo = leitorfunc.bi12_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     //$sql .= "      left join rechumanoescola on rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo";
     //$sql .= "      left join escola as escola2 on escola2.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      left join leitorpublico on leitorpublico.bi13_leitor = leitor.bi10_codigo";
     $sql .= "      left join cgm as cgmpub on cgmpub.z01_numcgm = leitorpublico.bi13_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($bi16_codigo!=null ){
         $sql2 .= " where carteira.bi16_codigo = $bi16_codigo ";
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
   function sql_query_file ( $bi16_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from carteira ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi16_codigo!=null ){
         $sql2 .= " where carteira.bi16_codigo = $bi16_codigo ";
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


  function sql_query_leitor($sNomeLeitor = '', $sCampos = '*', $sOrdem = '', $sDbWhere = '', $sDbWhereSub = '') {

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

    if (!empty($sDbWhereSub)) {
      $sDbWhereSub = ' where '.$sDbWhereSub;
    }

    $sSql .= " from ";
    $sSql .= " (select ov02_nome as nomeleitor, ov02_ident as identleitor, ov02_cnpjcpf as cpfleitor,";
    $sSql .= "          carteira.*, db_usuarios.*, leitorcategoria.*, biblioteca.*, leitor.* ";
    $sSql .= "     from carteira                                                                                           \n";
    $sSql .= "          inner join db_usuarios      on db_usuarios.id_usuario      = carteira.bi16_usuario                 \n";
    $sSql .= "          inner join leitorcategoria  on leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria         \n";
    $sSql .= "          inner join biblioteca       on biblioteca.bi17_codigo      = leitorcategoria.bi07_biblioteca       \n";
    $sSql .= "          inner join leitor           on leitor.bi10_codigo          = carteira.bi16_leitor                  \n";
    $sSql .= "          left  join leitorcidadao    on leitorcidadao.bi28_leitor   = leitor.bi10_codigo                    \n";
    $sSql .= "          left  join cidadao          on cidadao.ov02_sequencial     = leitorcidadao.bi28_cidadao_sequencial \n";
    $sSql .= "                                     and cidadao.ov02_seq            = leitorcidadao.bi28_cidadao_seq        \n";
    $sSql .= "       $sDbWhereSub) as leitor ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if (!empty($sNomeLeitor)) {
        $sSql2 .= " where leitor.nomeleitor like $sNomeLeitor ";
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

  function sql_query_leitorcidadao ( $bi16_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";

    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from carteira ";
    $sql .= "      inner join db_usuarios      on db_usuarios.id_usuario      = carteira.bi16_usuario                 \n";
    $sql .= "      inner join leitorcategoria  on leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria         \n";
    $sql .= "      inner join biblioteca       on biblioteca.bi17_codigo      = leitorcategoria.bi07_biblioteca       \n";
    $sql .= "      inner join leitor           on leitor.bi10_codigo          = carteira.bi16_leitor                  \n";
    $sql .= "      left  join leitorcidadao    on leitorcidadao.bi28_leitor   = leitor.bi10_codigo                    \n";
    $sql .= "      left  join cidadao          on cidadao.ov02_sequencial     = leitorcidadao.bi28_cidadao_sequencial \n";
    $sql .= "                                 and cidadao.ov02_seq            = leitorcidadao.bi28_cidadao_seq        \n";
    $sql2 = "";

    if ($dbwhere == "") {

      if ($bi16_codigo != null ) {
        $sql2 .= " where carteira.bi16_codigo = $bi16_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }

  public function sql_query_ultima_carteira( $id = null, $campos = "*", $ordem = null, $dbwhere = "", $iDepartamento, $iCategoria = null ) {

    $sql  = "select {$campos} ";
    $sql .= "  from ( ";
    $sql .= "         SELECT max(bi16_codigo) as bi16_codigo, bi16_leitor ";
    $sql .= "           from carteira ";
    $sql .= "           join leitorcategoria ON leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria ";
    $sql .= "           join biblioteca      ON biblioteca.bi17_codigo      = leitorcategoria.bi07_biblioteca ";
    $sql .= "          WHERE bi17_coddepto = {$iDepartamento} ";
    if ( !empty($iCategoria) ) {
      $sql .= "            and bi07_codigo = {$iCategoria} ";
    }
    $sql .= "          group by 2 ";
    $sql .= "       ) as x ";
    $sql .= " INNER JOIN carteira        on carteira.bi16_codigo        = x.bi16_codigo ";
    $sql .= " INNER JOIN leitorcategoria ON leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria ";
    $sql .= " INNER JOIN leitor          ON leitor.bi10_codigo          = x.bi16_leitor ";
    $sql .= "  LEFT JOIN leitorcidadao   ON leitorcidadao.bi28_leitor   = leitor.bi10_codigo ";
    $sql .= "  LEFT JOIN cidadao         ON cidadao.ov02_sequencial     = leitorcidadao.bi28_cidadao_sequencial ";
    $sql .= "                           AND cidadao.ov02_seq            = leitorcidadao.bi28_cidadao_seq      ";

    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($id)){
        $sql2 .= " where carteira.bi16_codigo = $id ";
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

}
?>