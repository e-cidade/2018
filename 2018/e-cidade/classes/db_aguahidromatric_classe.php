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

//MODULO: agua
//CLASSE DA ENTIDADE aguahidromatric
class cl_aguahidromatric {
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
   var $x04_codhidrometro = 0;
   var $x04_codmarca = 0;
   var $x04_nrohidro = null;
   var $x04_qtddigito = 0;
   var $x04_coddiametro = 0;
   var $x04_matric = 'null';
   var $x04_leitinicial = 0;
   var $x04_dtinst_dia = null;
   var $x04_dtinst_mes = null;
   var $x04_dtinst_ano = null;
   var $x04_dtinst = null;
   var $x04_avisoleiturista = null;
   var $x04_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 x04_codhidrometro = int4 = Código
                 x04_codmarca = int4 = Marca
                 x04_nrohidro = varchar(20) = Número
                 x04_qtddigito = int4 = Dígitos
                 x04_coddiametro = int4 = Diâmetro
                 x04_matric = int4 = Matrícula
                 x04_leitinicial = int8 = Leitura Inicial
                 x04_dtinst = date = Data instalação
                 x04_avisoleiturista = text = Aviso Leiturista
                 x04_observacao = text = Observações
                 ";
   //funcao construtor da classe
   function cl_aguahidromatric() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguahidromatric");
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
       $this->x04_codhidrometro = ($this->x04_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_codhidrometro"]:$this->x04_codhidrometro);
       $this->x04_codmarca = ($this->x04_codmarca == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_codmarca"]:$this->x04_codmarca);
       $this->x04_nrohidro = ($this->x04_nrohidro == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_nrohidro"]:$this->x04_nrohidro);
       $this->x04_qtddigito = ($this->x04_qtddigito == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_qtddigito"]:$this->x04_qtddigito);
       $this->x04_coddiametro = ($this->x04_coddiametro == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_coddiametro"]:$this->x04_coddiametro);
       $this->x04_matric = ($this->x04_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_matric"]:$this->x04_matric);
       $this->x04_leitinicial = ($this->x04_leitinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_leitinicial"]:$this->x04_leitinicial);
       if($this->x04_dtinst == ""){
         $this->x04_dtinst_dia = ($this->x04_dtinst_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_dtinst_dia"]:$this->x04_dtinst_dia);
         $this->x04_dtinst_mes = ($this->x04_dtinst_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_dtinst_mes"]:$this->x04_dtinst_mes);
         $this->x04_dtinst_ano = ($this->x04_dtinst_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_dtinst_ano"]:$this->x04_dtinst_ano);
         if($this->x04_dtinst_dia != ""){
            $this->x04_dtinst = $this->x04_dtinst_ano."-".$this->x04_dtinst_mes."-".$this->x04_dtinst_dia;
         }
       }
       $this->x04_avisoleiturista = ($this->x04_avisoleiturista == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_avisoleiturista"]:$this->x04_avisoleiturista);
       $this->x04_observacao = ($this->x04_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_observacao"]:$this->x04_observacao);
     }else{
       $this->x04_codhidrometro = ($this->x04_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x04_codhidrometro"]:$this->x04_codhidrometro);
     }
   }
   // funcao para inclusao
   function incluir ($x04_codhidrometro){
      $this->atualizacampos();
     if($this->x04_codmarca == null ){
       $this->erro_sql = " Campo Marca nao Informado.";
       $this->erro_campo = "x04_codmarca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x04_nrohidro == null ){
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "x04_nrohidro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x04_qtddigito == null ){
       $this->erro_sql = " Campo Dígitos nao Informado.";
       $this->erro_campo = "x04_qtddigito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x04_coddiametro == null ){
       $this->erro_sql = " Campo Diâmetro nao Informado.";
       $this->erro_campo = "x04_coddiametro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x04_leitinicial == null ){
       $this->erro_sql = " Campo Leitura Inicial nao Informado.";
       $this->erro_campo = "x04_leitinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x04_dtinst == null ){
       $this->erro_sql = " Campo Data instalação nao Informado.";
       $this->erro_campo = "x04_dtinst_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x04_codhidrometro == "" || $x04_codhidrometro == null ){
       $result = db_query("select nextval('aguahidromatric_x04_codhidrometro_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguahidromatric_x04_codhidrometro_seq do campo: x04_codhidrometro";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->x04_codhidrometro = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aguahidromatric_x04_codhidrometro_seq");
       if(($result != false) && (pg_result($result,0,0) < $x04_codhidrometro)){
         $this->erro_sql = " Campo x04_codhidrometro maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x04_codhidrometro = $x04_codhidrometro;
       }
     }
     if(($this->x04_codhidrometro == null) || ($this->x04_codhidrometro == "") ){
       $this->erro_sql = " Campo x04_codhidrometro nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguahidromatric(
                                       x04_codhidrometro
                                      ,x04_codmarca
                                      ,x04_nrohidro
                                      ,x04_qtddigito
                                      ,x04_coddiametro
                                      ,x04_matric
                                      ,x04_leitinicial
                                      ,x04_dtinst
                                      ,x04_avisoleiturista
                                      ,x04_observacao
                       )
                values (
                                $this->x04_codhidrometro
                               ,$this->x04_codmarca
                               ,'$this->x04_nrohidro'
                               ,$this->x04_qtddigito
                               ,$this->x04_coddiametro
                               ,$this->x04_matric
                               ,$this->x04_leitinicial
                               ,".($this->x04_dtinst == "null" || $this->x04_dtinst == ""?"null":"'".$this->x04_dtinst."'")."
                               ,'$this->x04_avisoleiturista'
                               ,'$this->x04_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguahidromatric ($this->x04_codhidrometro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguahidromatric já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguahidromatric ($this->x04_codhidrometro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x04_codhidrometro;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x04_codhidrometro));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8412,'$this->x04_codhidrometro','I')");
       $resac = db_query("insert into db_acount values($acount,1421,8412,'','".AddSlashes(pg_result($resaco,0,'x04_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8413,'','".AddSlashes(pg_result($resaco,0,'x04_codmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8414,'','".AddSlashes(pg_result($resaco,0,'x04_nrohidro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8415,'','".AddSlashes(pg_result($resaco,0,'x04_qtddigito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8424,'','".AddSlashes(pg_result($resaco,0,'x04_coddiametro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8432,'','".AddSlashes(pg_result($resaco,0,'x04_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8433,'','".AddSlashes(pg_result($resaco,0,'x04_leitinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,8434,'','".AddSlashes(pg_result($resaco,0,'x04_dtinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,18421,'','".AddSlashes(pg_result($resaco,0,'x04_avisoleiturista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1421,17926,'','".AddSlashes(pg_result($resaco,0,'x04_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($x04_codhidrometro=null) {
      $this->atualizacampos();
     $sql = " update aguahidromatric set ";
     $virgula = "";
     if(trim($this->x04_codhidrometro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_codhidrometro"])){
       $sql  .= $virgula." x04_codhidrometro = $this->x04_codhidrometro ";
       $virgula = ",";
       if(trim($this->x04_codhidrometro) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x04_codhidrometro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_codmarca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_codmarca"])){
       $sql  .= $virgula." x04_codmarca = $this->x04_codmarca ";
       $virgula = ",";
       if(trim($this->x04_codmarca) == null ){
         $this->erro_sql = " Campo Marca nao Informado.";
         $this->erro_campo = "x04_codmarca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_nrohidro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_nrohidro"])){
       $sql  .= $virgula." x04_nrohidro = '$this->x04_nrohidro' ";
       $virgula = ",";
       if(trim($this->x04_nrohidro) == null ){
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "x04_nrohidro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_qtddigito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_qtddigito"])){
       $sql  .= $virgula." x04_qtddigito = $this->x04_qtddigito ";
       $virgula = ",";
       if(trim($this->x04_qtddigito) == null ){
         $this->erro_sql = " Campo Dígitos nao Informado.";
         $this->erro_campo = "x04_qtddigito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_coddiametro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_coddiametro"])){
       $sql  .= $virgula." x04_coddiametro = $this->x04_coddiametro ";
       $virgula = ",";
       if(trim($this->x04_coddiametro) == null ){
         $this->erro_sql = " Campo Diâmetro nao Informado.";
         $this->erro_campo = "x04_coddiametro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_matric"])){
       $sql  .= $virgula." x04_matric = $this->x04_matric ";
       $virgula = ",";
     }
     if(trim($this->x04_leitinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_leitinicial"])){
       $sql  .= $virgula." x04_leitinicial = $this->x04_leitinicial ";
       $virgula = ",";
       if(trim($this->x04_leitinicial) == null ){
         $this->erro_sql = " Campo Leitura Inicial nao Informado.";
         $this->erro_campo = "x04_leitinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x04_dtinst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_dtinst_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x04_dtinst_dia"] !="") ){
       $sql  .= $virgula." x04_dtinst = '$this->x04_dtinst' ";
       $virgula = ",";
       if(trim($this->x04_dtinst) == null ){
         $this->erro_sql = " Campo Data instalação nao Informado.";
         $this->erro_campo = "x04_dtinst_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["x04_dtinst_dia"])){
         $sql  .= $virgula." x04_dtinst = null ";
         $virgula = ",";
         if(trim($this->x04_dtinst) == null ){
           $this->erro_sql = " Campo Data instalação nao Informado.";
           $this->erro_campo = "x04_dtinst_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x04_avisoleiturista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_avisoleiturista"])){
       $sql  .= $virgula." x04_avisoleiturista = '$this->x04_avisoleiturista' ";
       $virgula = ",";
     }
     if(trim($this->x04_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x04_observacao"])){
       $sql  .= $virgula." x04_observacao = '$this->x04_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x04_codhidrometro!=null){
       $sql .= " x04_codhidrometro = $this->x04_codhidrometro";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x04_codhidrometro));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8412,'$this->x04_codhidrometro','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_codhidrometro"]) || $this->x04_codhidrometro != "")
           $resac = db_query("insert into db_acount values($acount,1421,8412,'".AddSlashes(pg_result($resaco,$conresaco,'x04_codhidrometro'))."','$this->x04_codhidrometro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_codmarca"]) || $this->x04_codmarca != "")
           $resac = db_query("insert into db_acount values($acount,1421,8413,'".AddSlashes(pg_result($resaco,$conresaco,'x04_codmarca'))."','$this->x04_codmarca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_nrohidro"]) || $this->x04_nrohidro != "")
           $resac = db_query("insert into db_acount values($acount,1421,8414,'".AddSlashes(pg_result($resaco,$conresaco,'x04_nrohidro'))."','$this->x04_nrohidro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_qtddigito"]) || $this->x04_qtddigito != "")
           $resac = db_query("insert into db_acount values($acount,1421,8415,'".AddSlashes(pg_result($resaco,$conresaco,'x04_qtddigito'))."','$this->x04_qtddigito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_coddiametro"]) || $this->x04_coddiametro != "")
           $resac = db_query("insert into db_acount values($acount,1421,8424,'".AddSlashes(pg_result($resaco,$conresaco,'x04_coddiametro'))."','$this->x04_coddiametro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_matric"]) || $this->x04_matric != "")
           $resac = db_query("insert into db_acount values($acount,1421,8432,'".AddSlashes(pg_result($resaco,$conresaco,'x04_matric'))."','$this->x04_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_leitinicial"]) || $this->x04_leitinicial != "")
           $resac = db_query("insert into db_acount values($acount,1421,8433,'".AddSlashes(pg_result($resaco,$conresaco,'x04_leitinicial'))."','$this->x04_leitinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_dtinst"]) || $this->x04_dtinst != "")
           $resac = db_query("insert into db_acount values($acount,1421,8434,'".AddSlashes(pg_result($resaco,$conresaco,'x04_dtinst'))."','$this->x04_dtinst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_avisoleiturista"]) || $this->x04_avisoleiturista != "")
           $resac = db_query("insert into db_acount values($acount,1421,18421,'".AddSlashes(pg_result($resaco,$conresaco,'x04_avisoleiturista'))."','$this->x04_avisoleiturista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x04_observacao"]) || $this->x04_observacao != "")
           $resac = db_query("insert into db_acount values($acount,1421,17926,'".AddSlashes(pg_result($resaco,$conresaco,'x04_observacao'))."','$this->x04_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidromatric nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x04_codhidrometro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidromatric nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x04_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x04_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($x04_codhidrometro=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x04_codhidrometro));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8412,'$x04_codhidrometro','E')");
         $resac = db_query("insert into db_acount values($acount,1421,8412,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8413,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_codmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8414,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_nrohidro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8415,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_qtddigito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8424,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_coddiametro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8432,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8433,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_leitinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,8434,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_dtinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,18421,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_avisoleiturista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1421,17926,'','".AddSlashes(pg_result($resaco,$iresaco,'x04_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguahidromatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x04_codhidrometro != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x04_codhidrometro = $x04_codhidrometro ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidromatric nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x04_codhidrometro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidromatric nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x04_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x04_codhidrometro;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguahidromatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $x04_codhidrometro=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aguahidromatric ";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x04_codhidrometro!=null ){
         $sql2 .= " where aguahidromatric.x04_codhidrometro = $x04_codhidrometro ";
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

  public function sql_query_hidrometro($iCodigo = null, $sCampos = "*", $sOrder = null, $sWhere = null) {

    $sSql  = "select {$sCampos} ";
    $sSql .= " from aguahidromatric ";
    $sSql .= "      inner join aguahidromarca     on  aguahidromarca.x03_codmarca       = aguahidromatric.x04_codmarca ";
    $sSql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro ";
    $sSql .= "      left join aguabase            on  aguabase.x01_matric               = aguahidromatric.x04_matric ";
    $sSql .= "      left join bairro              on  bairro.j13_codi                   = aguabase.x01_codbairro ";
    $sSql .= "      left join ruas                on  ruas.j14_codigo                   = aguabase.x01_codrua ";
    $sSql .= "      left join cgm                 on  cgm.z01_numcgm                    = aguabase.x01_numcgm ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($iCodigo)) {
      $sSql .= " where aguahidromatric.x04_codhidrometro = {$iCodigo} ";
    }

    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

   // funcao do sql
   function sql_query_file ( $x04_codhidrometro=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aguahidromatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($x04_codhidrometro!=null ){
         $sql2 .= " where aguahidromatric.x04_codhidrometro = $x04_codhidrometro ";
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
   function sql_query_diametromarca ( $x04_codhidrometro=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aguahidromatric ";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql2 = "";
     if($dbwhere==""){
       if($x04_codhidrometro!=null ){
         $sql2 .= " where fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true and aguahidromatric.x04_codhidrometro = $x04_codhidrometro ";
       } else {
				 $sql2 .= " where fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true ";
			 }
     }else if($dbwhere != ""){
       $sql2 = " where fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true and $dbwhere";
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