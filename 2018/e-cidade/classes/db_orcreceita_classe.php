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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcreceita
class cl_orcreceita {
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
   var $o70_anousu = 0;
   var $o70_codrec = 0;
   var $o70_codfon = 0;
   var $o70_codigo = 0;
   var $o70_valor = 0;
   var $o70_reclan = 'f';
   var $o70_instit = 0;
   var $o70_concarpeculiar = null;
   var $o70_datacriacao_dia = null;
   var $o70_datacriacao_mes = null;
   var $o70_datacriacao_ano = null;
   var $o70_datacriacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o70_anousu = int4 = Exercício
                 o70_codrec = int4 = Código Reduzido
                 o70_codfon = int4 = Código Fonte
                 o70_codigo = int4 = Codigo do Recurso
                 o70_valor = float8 = Valor Previsto
                 o70_reclan = bool = Receita Lançada
                 o70_instit = int4 = Código da Instituição
                 o70_concarpeculiar = varchar(100) = Caracteristica Peculiar
                 o70_datacriacao = date = Data de Criação da Receita
                 ";
   //funcao construtor da classe
   function cl_orcreceita() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreceita");
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
       $this->o70_anousu = ($this->o70_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_anousu"]:$this->o70_anousu);
       $this->o70_codrec = ($this->o70_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_codrec"]:$this->o70_codrec);
       $this->o70_codfon = ($this->o70_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_codfon"]:$this->o70_codfon);
       $this->o70_codigo = ($this->o70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_codigo"]:$this->o70_codigo);
       $this->o70_valor = ($this->o70_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_valor"]:$this->o70_valor);
       $this->o70_reclan = ($this->o70_reclan == "f"?@$GLOBALS["HTTP_POST_VARS"]["o70_reclan"]:$this->o70_reclan);
       $this->o70_instit = ($this->o70_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_instit"]:$this->o70_instit);
       $this->o70_concarpeculiar = ($this->o70_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_concarpeculiar"]:$this->o70_concarpeculiar);
       if($this->o70_datacriacao == ""){
         $this->o70_datacriacao_dia = ($this->o70_datacriacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_dia"]:$this->o70_datacriacao_dia);
         $this->o70_datacriacao_mes = ($this->o70_datacriacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_mes"]:$this->o70_datacriacao_mes);
         $this->o70_datacriacao_ano = ($this->o70_datacriacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_ano"]:$this->o70_datacriacao_ano);
         if($this->o70_datacriacao_dia != ""){
            $this->o70_datacriacao = $this->o70_datacriacao_ano."-".$this->o70_datacriacao_mes."-".$this->o70_datacriacao_dia;
         }
       }
     }else{
       $this->o70_anousu = ($this->o70_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_anousu"]:$this->o70_anousu);
       $this->o70_codrec = ($this->o70_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o70_codrec"]:$this->o70_codrec);
     }
   }
   // funcao para inclusao
   function incluir ($o70_anousu,$o70_codrec){
      $this->atualizacampos();
     if($this->o70_codfon == null ){
       $this->erro_sql = " Campo Código Fonte nao Informado.";
       $this->erro_campo = "o70_codfon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_codigo == null ){
       $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
       $this->erro_campo = "o70_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_valor == null ){
       $this->erro_sql = " Campo Valor Previsto nao Informado.";
       $this->erro_campo = "o70_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_reclan == null ){
       $this->erro_sql = " Campo Receita Lançada nao Informado.";
       $this->erro_campo = "o70_reclan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_instit == null ){
       $this->erro_sql = " Campo Código da Instituição nao Informado.";
       $this->erro_campo = "o70_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_concarpeculiar == null ){
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "o70_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o70_datacriacao == null ){
       $this->o70_datacriacao = "null";
     }
     if($o70_codrec == "" || $o70_codrec == null ){
       $result = db_query("select nextval('orcreceita_o70_codrec_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcreceita_o70_codrec_seq do campo: o70_codrec";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->o70_codrec = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from orcreceita_o70_codrec_seq");
       if(($result != false) && (pg_result($result,0,0) < $o70_codrec)){
         $this->erro_sql = " Campo o70_codrec maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o70_codrec = $o70_codrec;
       }
     }
     if(($this->o70_anousu == null) || ($this->o70_anousu == "") ){
       $this->erro_sql = " Campo o70_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o70_codrec == null) || ($this->o70_codrec == "") ){
       $this->erro_sql = " Campo o70_codrec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreceita(
                                       o70_anousu
                                      ,o70_codrec
                                      ,o70_codfon
                                      ,o70_codigo
                                      ,o70_valor
                                      ,o70_reclan
                                      ,o70_instit
                                      ,o70_concarpeculiar
                                      ,o70_datacriacao
                       )
                values (
                                $this->o70_anousu
                               ,$this->o70_codrec
                               ,$this->o70_codfon
                               ,$this->o70_codigo
                               ,$this->o70_valor
                               ,'$this->o70_reclan'
                               ,$this->o70_instit
                               ,'$this->o70_concarpeculiar'
                               ,".($this->o70_datacriacao == "null" || $this->o70_datacriacao == ""?"null":"'".$this->o70_datacriacao."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas Orçamento ($this->o70_anousu."-".$this->o70_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas Orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas Orçamento ($this->o70_anousu."-".$this->o70_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o70_anousu."-".$this->o70_codrec;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o70_anousu,$this->o70_codrec));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5361,'$this->o70_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5367,'$this->o70_codrec','I')");
       $resac = db_query("insert into db_acount values($acount,780,5361,'','".AddSlashes(pg_result($resaco,0,'o70_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5367,'','".AddSlashes(pg_result($resaco,0,'o70_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5363,'','".AddSlashes(pg_result($resaco,0,'o70_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5364,'','".AddSlashes(pg_result($resaco,0,'o70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5362,'','".AddSlashes(pg_result($resaco,0,'o70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5365,'','".AddSlashes(pg_result($resaco,0,'o70_reclan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,5366,'','".AddSlashes(pg_result($resaco,0,'o70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,10818,'','".AddSlashes(pg_result($resaco,0,'o70_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,780,17690,'','".AddSlashes(pg_result($resaco,0,'o70_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o70_anousu=null,$o70_codrec=null) {
      $this->atualizacampos();
     $sql = " update orcreceita set ";
     $virgula = "";
     if(trim($this->o70_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_anousu"])){
       $sql  .= $virgula." o70_anousu = $this->o70_anousu ";
       $virgula = ",";
       if(trim($this->o70_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o70_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_codrec"])){
       $sql  .= $virgula." o70_codrec = $this->o70_codrec ";
       $virgula = ",";
       if(trim($this->o70_codrec) == null ){
         $this->erro_sql = " Campo Código Reduzido nao Informado.";
         $this->erro_campo = "o70_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_codfon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_codfon"])){
       $sql  .= $virgula." o70_codfon = $this->o70_codfon ";
       $virgula = ",";
       if(trim($this->o70_codfon) == null ){
         $this->erro_sql = " Campo Código Fonte nao Informado.";
         $this->erro_campo = "o70_codfon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_codigo"])){
       $sql  .= $virgula." o70_codigo = $this->o70_codigo ";
       $virgula = ",";
       if(trim($this->o70_codigo) == null ){
         $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
         $this->erro_campo = "o70_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_valor"])){
       $sql  .= $virgula." o70_valor = $this->o70_valor ";
       $virgula = ",";
       if(trim($this->o70_valor) == null ){
         $this->erro_sql = " Campo Valor Previsto nao Informado.";
         $this->erro_campo = "o70_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_reclan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_reclan"])){
       $sql  .= $virgula." o70_reclan = '$this->o70_reclan' ";
       $virgula = ",";
       if(trim($this->o70_reclan) == null ){
         $this->erro_sql = " Campo Receita Lançada nao Informado.";
         $this->erro_campo = "o70_reclan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_instit"])){
       $sql  .= $virgula." o70_instit = $this->o70_instit ";
       $virgula = ",";
       if(trim($this->o70_instit) == null ){
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "o70_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_concarpeculiar"])){
       $sql  .= $virgula." o70_concarpeculiar = '$this->o70_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->o70_concarpeculiar) == null ){
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "o70_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o70_datacriacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_dia"] !="") ){
       $sql  .= $virgula." o70_datacriacao = '$this->o70_datacriacao' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["o70_datacriacao_dia"])){
         $sql  .= $virgula." o70_datacriacao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($o70_anousu!=null){
       $sql .= " o70_anousu = $this->o70_anousu";
     }
     if($o70_codrec!=null){
       $sql .= " and  o70_codrec = $this->o70_codrec";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o70_anousu,$this->o70_codrec));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5361,'$this->o70_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5367,'$this->o70_codrec','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_anousu"]) || $this->o70_anousu != "")
           $resac = db_query("insert into db_acount values($acount,780,5361,'".AddSlashes(pg_result($resaco,$conresaco,'o70_anousu'))."','$this->o70_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_codrec"]) || $this->o70_codrec != "")
           $resac = db_query("insert into db_acount values($acount,780,5367,'".AddSlashes(pg_result($resaco,$conresaco,'o70_codrec'))."','$this->o70_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_codfon"]) || $this->o70_codfon != "")
           $resac = db_query("insert into db_acount values($acount,780,5363,'".AddSlashes(pg_result($resaco,$conresaco,'o70_codfon'))."','$this->o70_codfon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_codigo"]) || $this->o70_codigo != "")
           $resac = db_query("insert into db_acount values($acount,780,5364,'".AddSlashes(pg_result($resaco,$conresaco,'o70_codigo'))."','$this->o70_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_valor"]) || $this->o70_valor != "")
           $resac = db_query("insert into db_acount values($acount,780,5362,'".AddSlashes(pg_result($resaco,$conresaco,'o70_valor'))."','$this->o70_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_reclan"]) || $this->o70_reclan != "")
           $resac = db_query("insert into db_acount values($acount,780,5365,'".AddSlashes(pg_result($resaco,$conresaco,'o70_reclan'))."','$this->o70_reclan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_instit"]) || $this->o70_instit != "")
           $resac = db_query("insert into db_acount values($acount,780,5366,'".AddSlashes(pg_result($resaco,$conresaco,'o70_instit'))."','$this->o70_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_concarpeculiar"]) || $this->o70_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,780,10818,'".AddSlashes(pg_result($resaco,$conresaco,'o70_concarpeculiar'))."','$this->o70_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o70_datacriacao"]) || $this->o70_datacriacao != "")
           $resac = db_query("insert into db_acount values($acount,780,17690,'".AddSlashes(pg_result($resaco,$conresaco,'o70_datacriacao'))."','$this->o70_datacriacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas Orçamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o70_anousu."-".$this->o70_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas Orçamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o70_anousu."-".$this->o70_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o70_anousu."-".$this->o70_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o70_anousu=null,$o70_codrec=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o70_anousu,$o70_codrec));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5361,'$o70_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5367,'$o70_codrec','E')");
         $resac = db_query("insert into db_acount values($acount,780,5361,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5367,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5363,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5364,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5362,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5365,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_reclan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,5366,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,10818,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,780,17690,'','".AddSlashes(pg_result($resaco,$iresaco,'o70_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o70_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o70_anousu = $o70_anousu ";
        }
        if($o70_codrec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o70_codrec = $o70_codrec ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas Orçamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o70_anousu."-".$o70_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas Orçamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o70_anousu."-".$o70_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o70_anousu."-".$o70_codrec;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o70_anousu=null,$o70_codrec=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcreceita ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($o70_anousu!=null ){
         $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
       }
       if($o70_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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
   function sql_query_file ( $o70_anousu=null,$o70_codrec=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcreceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o70_anousu!=null ){
         $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
       }
       if($o70_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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
   function sql_query_migra($anousu,$instit){
    $sql ="select
		   o70_anousu,
		   o70_codrec,
		   o70_codfon,
		   o57_fonte,
		   o57_descr,
		   o70_valor,
		   sum(jan) as jan,
		   sum(fev) as fev,
		   sum(mar) as mar,
		   sum(abr) as abr,
		   sum(mai) as mai,
		   sum(jun) as jun,
		   sum(jul) as jul,
		   sum(ago) as ago,
		   sum(set) as set,
		   sum(out) as out,
		   sum(nov) as nov,
		   sum(dez) as dez
	   from (
		select
		   o70_anousu,
		   o70_codrec,
		   o70_codfon,
		   o57_fonte,
		   o57_descr,
		   o70_valor,
		   case when o71_mes=1 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as jan,
		   case when o71_mes=2 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as fev,
		   case when o71_mes=3 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as mar,
		   case when o71_mes=4 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as abr,
		   case when o71_mes=5 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as mai,
		   case when o71_mes=6 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as jun,
		   case when o71_mes=7 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as jul,
		   case when o71_mes=8 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as ago,
		   case when o71_mes=9 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as set,
		   case when o71_mes=10 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as out,
		   case when o71_mes=11 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as nov,
		   case when o71_mes=12 then sum(case when o71_coddoc=100 then o71_valor else o71_valor*-1 end) end as dez

		from orcreceita
		   left join orcreceitaval on o71_codrec=o70_codrec and o71_anousu=o70_anousu
		   left join orcfontes on o57_codfon=o70_codfon and o57_anousu=o70_anousu

		where o70_anousu=$anousu
		      and o70_instit=$instit

		group by
		   o70_anousu,
		   o70_codrec,
		   o70_codfon,
		   o57_fonte,
		   o57_descr,
		   o71_mes,
		   o70_valor

		) as x
	group by
	   o70_anousu,
	   o70_codrec,
	   o70_codfon,
	   o57_fonte,
	   o57_descr,
	   o70_valor
          ";
     return $sql;

  } // end function
   function sql_query_plano ( $o70_anousu=null,$o70_codrec=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcreceita ";
     $sql .= "      inner join db_config      on  db_config.codigo      = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec     on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes      on  orcfontes.o57_codfon  = orcreceita.o70_codfon ";
     $sql .= "                               and  orcfontes.o57_anousu  = orcreceita.o70_anousu";
     $sql .= "      inner join conplanoreduz  on  orcfontes.o57_codfon  = conplanoreduz.c61_codcon ";
     $sql .= "                               and  orcfontes.o57_anousu  = conplanoreduz.c61_anousu";
     $sql .= "                               and  orcreceita.o70_instit = conplanoreduz.c61_instit";
     $sql .= "      inner join concarpeculiar on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($o70_anousu!=null ){
         $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
       }
       if($o70_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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
     return analiseQueryPlanoOrcamento($sql);
  }
   function sql_query_razao( $o70_anousu=null,$o70_codrec=null,$campos="*",$ordem=null,$dbwhere=""){
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
  $sql .= " from orcreceita ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit and db_config.codigo = ".db_getsession("DB_instit");
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and orcfontes.o57_anousu = orcreceita.o70_anousu ";
     $sql2 = "";
  if($dbwhere==""){
       if($o70_anousu!=null ){
         $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
       }
       if($o70_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
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
  function sql_query_atualizacoesprevisao ( $o70_anousu=null,$o70_codrec=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "       FROM orcreceita                                                        ";
     $sql .= " INNER JOIN db_config    ON db_config.codigo        = orcreceita.o70_instit   ";
     $sql .= " INNER JOIN orcfontes    ON orcfontes.o57_codfon    = orcreceita.o70_codfon   ";
     $sql .= "                        AND orcfontes.o57_anousu    = orcreceita.o70_anousu   ";
     $sql .= " INNER JOIN orcsuplemrec ON orcsuplemrec.o85_anousu = orcreceita.o70_anousu   ";
     $sql .= "                        AND orcsuplemrec.o85_codrec = orcreceita.o70_codrec   ";
     $sql .= " INNER JOIN orcsuplem    ON orcsuplem.o46_codsup    = orcsuplemrec.o85_codsup ";
     $sql .= " INNER JOIN orcsuplemlan ON orcsuplemlan.o49_codsup = orcsuplem.o46_codsup    ";
     $sql2 = "";
     if($dbwhere==""){
       if($o70_anousu!=null ){
         $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
       }
       if($o70_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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




  function sql_query_dados_receita( $o70_anousu = null, $o70_codrec = null, $campos= "*", $ordem = null, $dbwhere = "") {


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
    $sql .= " from orcreceita ";
    $sql .= "      inner join db_config                 on db_config.codigo                                = orcreceita.o70_instit                   ";
    $sql .= "                                          and db_config.codigo                                = ".db_getsession("DB_instit")             ;
    $sql .= "      inner join orcfontes                 on orcfontes.o57_codfon                            = orcreceita.o70_codfon                   ";
    $sql .= "                                          and orcfontes.o57_anousu                            = orcreceita.o70_anousu                   ";
    $sql .= "      inner join conplanoorcamento         on orcfontes.o57_codfon                            = conplanoorcamento.c60_codcon            ";
    $sql .= "                                          and orcfontes.o57_anousu                            = conplanoorcamento.c60_anousu                   ";


    $sql2 = "";
    if ($dbwhere == "") {
      if($o70_anousu!=null ){
        $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
      }
      if($o70_codrec!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql.$sql2;
  }

  /**
   * query criada para atendener a nova rotina de receita fato gerador
   * será incluso join com as tabelas criadas :
   * aberturaexercicio
   * conlancamaberturaexercicio
   */
  function sql_queryEstornoReceitaFatoGerador ( $o70_anousu = null, $o70_codrec = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";

    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }

    } else {

      $sql .= $campos;
    }

    $sql .= " from orcreceita                                                                                                                   ";
    $sql .= "      inner join db_config                  on db_config.codigo              = orcreceita.o70_instit                               ";
    $sql .= "      inner join orctiporec                 on orctiporec.o15_codigo         = orcreceita.o70_codigo                               ";
    $sql .= "      inner join orcfontes                  on orcfontes.o57_codfon          = orcreceita.o70_codfon                               ";
    $sql .= "                                           and orcfontes.o57_anousu          = orcreceita.o70_anousu                               ";
    $sql .= "      inner join concarpeculiar             on concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar                       ";
    $sql .= "      inner join cgm                        on cgm.z01_numcgm                = db_config.numcgm                                    ";
    $sql .= "      inner join db_tipoinstit              on db_tipoinstit.db21_codtipo    = db_config.db21_tipoinstit                           ";
    $sql .= "      inner join conlancamrec               on orcreceita.o70_codrec         = conlancamrec.c74_codrec                             ";
    $sql .= "                                           and orcreceita.o70_anousu         = conlancamrec.c74_anousu                             ";
    $sql .= "      inner join conlancamaberturaexercicio on conlancamrec.c74_codlan       = conlancamaberturaexercicio.c80_conlancam            ";
    $sql .= "      inner join aberturaexercicio          on conlancamaberturaexercicio.c80_aberturaexercicio = aberturaexercicio.c81_sequencial ";
    $sql .= "                                           and aberturaexercicio.c81_estornado = false                                             ";
    $sql .= "      inner join conlancam                  on conlancamrec.c74_codlan         = conlancam.c70_codlan                              ";

    $sql2 = "";

    if($dbwhere==""){
      if($o70_anousu!=null ){
        $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
      }
      if($o70_codrec!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
        $sql2 .= " where ";
      }
        $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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

  function sql_query_fonte_desdobramento($o70_anousu = null, $o70_codrec = null, $campos = "*", $ordem = null, $dbwhere = "") {

  	$sql = "select ";

  	if($campos != "*" ) {

  		$campos_sql = split("#",$campos);
  		$virgula    = "";

  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql    .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}

  	} else {

  		$sql .= $campos;
  	}

  	$sql .= " from orcreceita                                                                     ";
    $sql .= "      inner join orcfontes      on orcfontes.o57_codfon    = orcreceita.o70_codfon   ";
    $sql .= "                               and orcfontes.o57_anousu    = orcreceita.o70_anousu   ";
    $sql .= "      inner join orcfontesdes   on orcfontesdes.o60_anousu = orcreceita.o70_anousu   ";
    $sql .= "                               and orcfontes.o57_codfon    = orcfontesdes.o60_codfon ";

  	$sql2 = "";

  	if($dbwhere==""){
  		if($o70_anousu!=null ){
  			$sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
  		}
  		if($o70_codrec!=null ){
  			if($sql2!=""){
  				$sql2 .= " and ";
  			}else{
  				$sql2 .= " where ";
  			}
  			$sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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


  function sql_query_validacao_receita($o70_anousu = null, $o70_codrec = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";

    if($campos != "*" ) {

    		$campos_sql = split("#",$campos);
    		$virgula    = "";

    		for ($i = 0; $i < sizeof($campos_sql); $i++) {

    		  $sql    .= $virgula.$campos_sql[$i];
    		  $virgula = ",";
    		}

    } else {

    		$sql .= $campos;
    }

    $sql .= " from orcreceita ";
    $sql .= "      inner join db_config                 on db_config.codigo                 = orcreceita.o70_instit                   ";
    $sql .= "                                          and db_config.codigo                 = ".db_getsession("DB_instit")             ;
    $sql .= "      inner join orcfontes                 on orcfontes.o57_codfon             = orcreceita.o70_codfon                   ";
    $sql .= "                                          and orcfontes.o57_anousu             = orcreceita.o70_anousu                   ";
    $sql .= "      inner join conplanoorcamento         on orcfontes.o57_codfon             = conplanoorcamento.c60_codcon            ";
    $sql .= "                                          and orcfontes.o57_anousu             = conplanoorcamento.c60_anousu            ";

    $sql .= "      left join conplanoorcamentogrupo    on conplanoorcamentogrupo.c21_codcon = conplanoorcamento.c60_codcon ";
    $sql .= "                                         and conplanoorcamentogrupo.c21_anousu = conplanoorcamento.c60_anousu ";

    $sql.= "        left join conplanoconplanoorcamento   on c72_conplanoorcamento   = conplanoorcamento.c60_codcon ";
    $sql.= "                                              and c72_anousu             = conplanoorcamento.c60_anousu ";
    $sql.= "        left join conplano                    on c72_conplano            = conplano.c60_codcon ";
    $sql.= "                                              and c72_anousu             = conplano.c60_anousu ";
    $sql.= "        left join conplanoreduz               on c61_codcon              = conplano.c60_codcon ";
    $sql.= "                                              and c61_anousu             = conplano.c60_anousu ";




    $sql2 = "";

    if($dbwhere==""){
    		if($o70_anousu!=null ){
    		  $sql2 .= " where orcreceita.o70_anousu = $o70_anousu ";
    		}
    		if($o70_codrec!=null ){
    		  if($sql2!=""){
    		    $sql2 .= " and ";
    		  }else{
    		    $sql2 .= " where ";
    		  }
    		  $sql2 .= " orcreceita.o70_codrec = $o70_codrec ";
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

  /**
   * @param string $campos
   * @param null $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_receita($campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    $sql .= $campos;

    $sql .= " from orcreceita                                                                     ";
    $sql .= "      inner join orcfontes      on orcfontes.o57_codfon    = orcreceita.o70_codfon   ";
    $sql .= "                               and orcfontes.o57_anousu    = orcreceita.o70_anousu   ";

    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    }
    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }

    return $sql;
  }
}