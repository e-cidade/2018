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

//MODULO: caixa
//CLASSE DA ENTIDADE placaixarec
class cl_placaixarec {
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
   var $k81_seqpla = 0;
   var $k81_codpla = 0;
   var $k81_conta = 0;
   var $k81_receita = 0;
   var $k81_valor = 0;
   var $k81_obs = null;
   var $k81_codigo = 0;
   var $k81_datareceb_dia = null;
   var $k81_datareceb_mes = null;
   var $k81_datareceb_ano = null;
   var $k81_datareceb = null;
   var $k81_operbanco = null;
   var $k81_origem = 0;
   var $k81_numcgm = 0;
   var $k81_concarpeculiar = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k81_seqpla = int4 = Lançamento 
                 k81_codpla = int4 = PLanilha 
                 k81_conta = int4 = Código Conta 
                 k81_receita = int4 = codigo da receita 
                 k81_valor = float8 = Valor 
                 k81_obs = text = Observação 
                 k81_codigo = int4 = Recurso 
                 k81_datareceb = date = Data Recebimento 
                 k81_operbanco = varchar(20) = Operação banco 
                 k81_origem = int4 = Origem 
                 k81_numcgm = int4 = CGM do Contribuinte 
                 k81_concarpeculiar = varchar(100) = C. Peculiar/C. Aplicação 
                 ";
   //funcao construtor da classe
   function cl_placaixarec() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("placaixarec");
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
       $this->k81_seqpla = ($this->k81_seqpla == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_seqpla"]:$this->k81_seqpla);
       $this->k81_codpla = ($this->k81_codpla == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_codpla"]:$this->k81_codpla);
       $this->k81_conta = ($this->k81_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_conta"]:$this->k81_conta);
       $this->k81_receita = ($this->k81_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_receita"]:$this->k81_receita);
       $this->k81_valor = ($this->k81_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_valor"]:$this->k81_valor);
       $this->k81_obs = ($this->k81_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_obs"]:$this->k81_obs);
       $this->k81_codigo = ($this->k81_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_codigo"]:$this->k81_codigo);
       if($this->k81_datareceb == ""){
         $this->k81_datareceb_dia = ($this->k81_datareceb_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_datareceb_dia"]:$this->k81_datareceb_dia);
         $this->k81_datareceb_mes = ($this->k81_datareceb_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_datareceb_mes"]:$this->k81_datareceb_mes);
         $this->k81_datareceb_ano = ($this->k81_datareceb_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_datareceb_ano"]:$this->k81_datareceb_ano);
         if($this->k81_datareceb_dia != ""){
            $this->k81_datareceb = $this->k81_datareceb_ano."-".$this->k81_datareceb_mes."-".$this->k81_datareceb_dia;
         }
       }
       $this->k81_operbanco = ($this->k81_operbanco == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_operbanco"]:$this->k81_operbanco);
       $this->k81_origem = ($this->k81_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_origem"]:$this->k81_origem);
       $this->k81_numcgm = ($this->k81_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_numcgm"]:$this->k81_numcgm);
       $this->k81_concarpeculiar = ($this->k81_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_concarpeculiar"]:$this->k81_concarpeculiar);
     }else{
       $this->k81_seqpla = ($this->k81_seqpla == ""?@$GLOBALS["HTTP_POST_VARS"]["k81_seqpla"]:$this->k81_seqpla);
     }
   }
   // funcao para inclusao
   function incluir ($k81_seqpla){
      $this->atualizacampos();
     if($this->k81_codpla == null ){
       $this->erro_sql = " Campo PLanilha nao Informado.";
       $this->erro_campo = "k81_codpla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_conta == null ){
       $this->erro_sql = " Campo Código Conta nao Informado.";
       $this->erro_campo = "k81_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_receita == null ){
       $this->erro_sql = " Campo codigo da receita nao Informado.";
       $this->erro_campo = "k81_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k81_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_codigo == null ){
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "k81_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_datareceb == null ){
       $this->erro_sql = " Campo Data Recebimento nao Informado.";
       $this->erro_campo = "k81_datareceb_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_origem == null ){
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "k81_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_numcgm == null ){
       $this->erro_sql = " Campo CGM do Contribuinte nao Informado.";
       $this->erro_campo = "k81_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k81_concarpeculiar == null ){
       $this->erro_sql = " Campo C. Peculiar/C. Aplicação nao Informado.";
       $this->erro_campo = "k81_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k81_seqpla == "" || $k81_seqpla == null ){
       $result = db_query("select nextval('placaixarec_k81_seqpla_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: placaixarec_k81_seqpla_seq do campo: k81_seqpla";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k81_seqpla = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from placaixarec_k81_seqpla_seq");
       if(($result != false) && (pg_result($result,0,0) < $k81_seqpla)){
         $this->erro_sql = " Campo k81_seqpla maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k81_seqpla = $k81_seqpla;
       }
     }
     if(($this->k81_seqpla == null) || ($this->k81_seqpla == "") ){
       $this->erro_sql = " Campo k81_seqpla nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into placaixarec(
                                       k81_seqpla 
                                      ,k81_codpla 
                                      ,k81_conta 
                                      ,k81_receita 
                                      ,k81_valor 
                                      ,k81_obs 
                                      ,k81_codigo 
                                      ,k81_datareceb 
                                      ,k81_operbanco 
                                      ,k81_origem 
                                      ,k81_numcgm 
                                      ,k81_concarpeculiar 
                       )
                values (
                                $this->k81_seqpla 
                               ,$this->k81_codpla 
                               ,$this->k81_conta 
                               ,$this->k81_receita 
                               ,$this->k81_valor 
                               ,'$this->k81_obs' 
                               ,$this->k81_codigo 
                               ,".($this->k81_datareceb == "null" || $this->k81_datareceb == ""?"null":"'".$this->k81_datareceb."'")." 
                               ,'$this->k81_operbanco' 
                               ,$this->k81_origem 
                               ,$this->k81_numcgm 
                               ,'$this->k81_concarpeculiar' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas das planilhas de caixa ($this->k81_seqpla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas das planilhas de caixa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas das planilhas de caixa ($this->k81_seqpla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k81_seqpla;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k81_seqpla));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6300,'$this->k81_seqpla','I')");
       $resac = db_query("insert into db_acount values($acount,1024,6300,'','".AddSlashes(pg_result($resaco,0,'k81_seqpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,6292,'','".AddSlashes(pg_result($resaco,0,'k81_codpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,6293,'','".AddSlashes(pg_result($resaco,0,'k81_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,6294,'','".AddSlashes(pg_result($resaco,0,'k81_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,6295,'','".AddSlashes(pg_result($resaco,0,'k81_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,6302,'','".AddSlashes(pg_result($resaco,0,'k81_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,9780,'','".AddSlashes(pg_result($resaco,0,'k81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,9781,'','".AddSlashes(pg_result($resaco,0,'k81_datareceb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,9782,'','".AddSlashes(pg_result($resaco,0,'k81_operbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,11832,'','".AddSlashes(pg_result($resaco,0,'k81_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,11833,'','".AddSlashes(pg_result($resaco,0,'k81_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1024,18134,'','".AddSlashes(pg_result($resaco,0,'k81_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k81_seqpla=null) {
      $this->atualizacampos();
     $sql = " update placaixarec set ";
     $virgula = "";
     if(trim($this->k81_seqpla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_seqpla"])){
       $sql  .= $virgula." k81_seqpla = $this->k81_seqpla ";
       $virgula = ",";
       if(trim($this->k81_seqpla) == null ){
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "k81_seqpla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_codpla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_codpla"])){
       $sql  .= $virgula." k81_codpla = $this->k81_codpla ";
       $virgula = ",";
       if(trim($this->k81_codpla) == null ){
         $this->erro_sql = " Campo PLanilha nao Informado.";
         $this->erro_campo = "k81_codpla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_conta"])){
       $sql  .= $virgula." k81_conta = $this->k81_conta ";
       $virgula = ",";
       if(trim($this->k81_conta) == null ){
         $this->erro_sql = " Campo Código Conta nao Informado.";
         $this->erro_campo = "k81_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_receita"])){
       $sql  .= $virgula." k81_receita = $this->k81_receita ";
       $virgula = ",";
       if(trim($this->k81_receita) == null ){
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "k81_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_valor"])){
       $sql  .= $virgula." k81_valor = $this->k81_valor ";
       $virgula = ",";
       if(trim($this->k81_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k81_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_obs"])){
       $sql  .= $virgula." k81_obs = '$this->k81_obs' ";
       $virgula = ",";
     }
     if(trim($this->k81_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_codigo"])){
       $sql  .= $virgula." k81_codigo = $this->k81_codigo ";
       $virgula = ",";
       if(trim($this->k81_codigo) == null ){
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "k81_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_datareceb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_datareceb_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k81_datareceb_dia"] !="") ){
       $sql  .= $virgula." k81_datareceb = '$this->k81_datareceb' ";
       $virgula = ",";
       if(trim($this->k81_datareceb) == null ){
         $this->erro_sql = " Campo Data Recebimento nao Informado.";
         $this->erro_campo = "k81_datareceb_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k81_datareceb_dia"])){
         $sql  .= $virgula." k81_datareceb = null ";
         $virgula = ",";
         if(trim($this->k81_datareceb) == null ){
           $this->erro_sql = " Campo Data Recebimento nao Informado.";
           $this->erro_campo = "k81_datareceb_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k81_operbanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_operbanco"])){
       $sql  .= $virgula." k81_operbanco = '$this->k81_operbanco' ";
       $virgula = ",";
     }
     if(trim($this->k81_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_origem"])){
       $sql  .= $virgula." k81_origem = $this->k81_origem ";
       $virgula = ",";
       if(trim($this->k81_origem) == null ){
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "k81_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_numcgm"])){
       $sql  .= $virgula." k81_numcgm = $this->k81_numcgm ";
       $virgula = ",";
       if(trim($this->k81_numcgm) == null ){
         $this->erro_sql = " Campo CGM do Contribuinte nao Informado.";
         $this->erro_campo = "k81_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k81_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k81_concarpeculiar"])){
       $sql  .= $virgula." k81_concarpeculiar = '$this->k81_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->k81_concarpeculiar) == null ){
         $this->erro_sql = " Campo C. Peculiar/C. Aplicação nao Informado.";
         $this->erro_campo = "k81_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k81_seqpla!=null){
       $sql .= " k81_seqpla = $this->k81_seqpla";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k81_seqpla));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6300,'$this->k81_seqpla','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_seqpla"]) || $this->k81_seqpla != "")
           $resac = db_query("insert into db_acount values($acount,1024,6300,'".AddSlashes(pg_result($resaco,$conresaco,'k81_seqpla'))."','$this->k81_seqpla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_codpla"]) || $this->k81_codpla != "")
           $resac = db_query("insert into db_acount values($acount,1024,6292,'".AddSlashes(pg_result($resaco,$conresaco,'k81_codpla'))."','$this->k81_codpla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_conta"]) || $this->k81_conta != "")
           $resac = db_query("insert into db_acount values($acount,1024,6293,'".AddSlashes(pg_result($resaco,$conresaco,'k81_conta'))."','$this->k81_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_receita"]) || $this->k81_receita != "")
           $resac = db_query("insert into db_acount values($acount,1024,6294,'".AddSlashes(pg_result($resaco,$conresaco,'k81_receita'))."','$this->k81_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_valor"]) || $this->k81_valor != "")
           $resac = db_query("insert into db_acount values($acount,1024,6295,'".AddSlashes(pg_result($resaco,$conresaco,'k81_valor'))."','$this->k81_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_obs"]) || $this->k81_obs != "")
           $resac = db_query("insert into db_acount values($acount,1024,6302,'".AddSlashes(pg_result($resaco,$conresaco,'k81_obs'))."','$this->k81_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_codigo"]) || $this->k81_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1024,9780,'".AddSlashes(pg_result($resaco,$conresaco,'k81_codigo'))."','$this->k81_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_datareceb"]) || $this->k81_datareceb != "")
           $resac = db_query("insert into db_acount values($acount,1024,9781,'".AddSlashes(pg_result($resaco,$conresaco,'k81_datareceb'))."','$this->k81_datareceb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_operbanco"]) || $this->k81_operbanco != "")
           $resac = db_query("insert into db_acount values($acount,1024,9782,'".AddSlashes(pg_result($resaco,$conresaco,'k81_operbanco'))."','$this->k81_operbanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_origem"]) || $this->k81_origem != "")
           $resac = db_query("insert into db_acount values($acount,1024,11832,'".AddSlashes(pg_result($resaco,$conresaco,'k81_origem'))."','$this->k81_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_numcgm"]) || $this->k81_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1024,11833,'".AddSlashes(pg_result($resaco,$conresaco,'k81_numcgm'))."','$this->k81_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k81_concarpeculiar"]) || $this->k81_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,1024,18134,'".AddSlashes(pg_result($resaco,$conresaco,'k81_concarpeculiar'))."','$this->k81_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas das planilhas de caixa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k81_seqpla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas das planilhas de caixa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k81_seqpla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k81_seqpla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k81_seqpla=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k81_seqpla));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6300,'$k81_seqpla','E')");
         $resac = db_query("insert into db_acount values($acount,1024,6300,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_seqpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,6292,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_codpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,6293,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,6294,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,6295,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,6302,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,9780,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,9781,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_datareceb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,9782,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_operbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,11832,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,11833,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1024,18134,'','".AddSlashes(pg_result($resaco,$iresaco,'k81_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from placaixarec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k81_seqpla != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k81_seqpla = $k81_seqpla ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas das planilhas de caixa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k81_seqpla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas das planilhas de caixa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k81_seqpla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k81_seqpla;
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
        $this->erro_sql   = "Record Vazio na Tabela:placaixarec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k81_seqpla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from placaixarec ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = placaixarec.k81_numcgm";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = placaixarec.k81_receita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = placaixarec.k81_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = placaixarec.k81_codigo";
     $sql .= "      inner join placaixa  on  placaixa.k80_codpla = placaixarec.k81_codpla";
     $sql .= "      LEFT  join concarpeculiar  on  concarpeculiar.c58_sequencial = placaixarec.k81_concarpeculiar";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join db_config  on  db_config.codigo = placaixa.k80_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k81_seqpla!=null ){
         $sql2 .= " where placaixarec.k81_seqpla = $k81_seqpla ";
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
   function sql_query_file ( $k81_seqpla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from placaixarec ";
     $sql2 = "";
     if($dbwhere==""){
       if($k81_seqpla!=null ){
         $sql2 .= " where placaixarec.k81_seqpla = $k81_seqpla ";
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
   function sql_query_matric_inscr ( $k81_seqpla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from placaixarec ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = placaixarec.k81_numcgm";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = placaixarec.k81_receita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = placaixarec.k81_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = placaixarec.k81_codigo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = placaixarec.k81_concarpeculiar";
     $sql .= "      inner join placaixa  on  placaixa.k80_codpla = placaixarec.k81_codpla";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_config  on  db_config.codigo = placaixa.k80_instit";
     $sql .= "      left  join placaixarecmatric  on  placaixarec.k81_seqpla = placaixarecmatric.k77_placaixarec";
     $sql .= "      left  join iptubase  on  k77_matric = j01_matric";
     $sql .= "      left  join cgm as cgmmatric  on  j01_numcgm = cgmmatric.z01_numcgm";
     $sql .= "      left  join placaixarecinscr  on  placaixarec.k81_seqpla = placaixarecinscr.k76_placaixarec";
     $sql .= "      left  join issbase  on  k76_inscr = q02_inscr";
     $sql .= "      left  join cgm as cgminscr  on  q02_numcgm = cgminscr.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k81_seqpla!=null ){
         $sql2 .= " where placaixarec.k81_seqpla = $k81_seqpla ";
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

  function sql_query_origem( $k81_seqpla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from placaixarec ";
     $sql .= "      left  join placaixarecmatric on  placaixarec.k81_seqpla = placaixarecmatric.k77_placaixarec";
     $sql .= "      left  join placaixarecinscr  on  placaixarec.k81_seqpla = placaixarecinscr.k76_placaixarec";
     $sql2 = "";
     if($dbwhere==""){
       if($k81_seqpla!=null ){
         $sql2 .= " where placaixarec.k81_seqpla = $k81_seqpla ";
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
   * @param string $sCampos
   * @param null   $sOrder
   * @param null   $sWhere
   *
   * @return string
   */
  public function sql_query_planilha_autenticada($sCampos = "*", $sOrder = null, $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from placaixarec ";
    $sSql .= "        inner join corplacaixa on corplacaixa.k82_seqpla = placaixarec.k81_seqpla";
    $sSql .= "        inner join conlancamcorrente on conlancamcorrente.c86_id     = corplacaixa.k82_id ";
    $sSql .= "                                    and conlancamcorrente.c86_data   = corplacaixa.k82_data ";
    $sSql .= "                                    and conlancamcorrente.c86_autent = corplacaixa.k82_autent ";
    $sSql .= "        inner join conlancam on conlancam.c70_codlan = conlancamcorrente.c86_conlancam";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }
}