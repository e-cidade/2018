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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoorcamento
class cl_conplanoorcamento {
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
   var $c60_codcon = 0;
   var $c60_anousu = 0;
   var $c60_estrut = null;
   var $c60_descr = null;
   var $c60_finali = null;
   var $c60_codsis = 0;
   var $c60_codcla = 0;
   var $c60_consistemaconta = 0;
   var $c60_identificadorfinanceiro = null;
   var $c60_naturezasaldo = 0;
   var $c60_funcao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c60_codcon = int4 = Código
                 c60_anousu = int4 = Exercício
                 c60_estrut = varchar(15) = Estrutural
                 c60_descr = varchar(50) = Descrição da conta
                 c60_finali = text = Finalidade
                 c60_codsis = int4 = Sistema
                 c60_codcla = int4 = Classificação
                 c60_consistemaconta = int4 = consistemaconta
                 c60_identificadorfinanceiro = char(1) = Identificador financeiro
                 c60_naturezasaldo = int4 = naturezasaldo
                 c60_funcao = text = Função
                 ";
   //funcao construtor da classe
   function cl_conplanoorcamento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoorcamento");
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
       $this->c60_codcon = ($this->c60_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcon"]:$this->c60_codcon);
       $this->c60_anousu = ($this->c60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_anousu"]:$this->c60_anousu);
       $this->c60_estrut = ($this->c60_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_estrut"]:$this->c60_estrut);
       $this->c60_descr = ($this->c60_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_descr"]:$this->c60_descr);
       $this->c60_finali = ($this->c60_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_finali"]:$this->c60_finali);
       $this->c60_codsis = ($this->c60_codsis == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codsis"]:$this->c60_codsis);
       $this->c60_codcla = ($this->c60_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcla"]:$this->c60_codcla);
       $this->c60_consistemaconta = ($this->c60_consistemaconta == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"]:$this->c60_consistemaconta);
       $this->c60_identificadorfinanceiro = ($this->c60_identificadorfinanceiro == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"]:$this->c60_identificadorfinanceiro);
       $this->c60_naturezasaldo = ($this->c60_naturezasaldo == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"]:$this->c60_naturezasaldo);
       $this->c60_funcao = ($this->c60_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_funcao"]:$this->c60_funcao);
     }else{
       $this->c60_codcon = ($this->c60_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcon"]:$this->c60_codcon);
       $this->c60_anousu = ($this->c60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_anousu"]:$this->c60_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($c60_codcon,$c60_anousu){
      $this->atualizacampos();
     if($this->c60_estrut == null ){
       $this->erro_sql = " Campo Estrutural nao Informado.";
       $this->erro_campo = "c60_estrut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_descr == null ){
       $this->erro_sql = " Campo Descrição da conta nao Informado.";
       $this->erro_campo = "c60_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_codsis == null ){
       $this->erro_sql = " Campo Sistema nao Informado.";
       $this->erro_campo = "c60_codsis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_codcla == null ){
       $this->erro_sql = " Campo Classificação nao Informado.";
       $this->erro_campo = "c60_codcla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_consistemaconta == null ){
       $this->erro_sql = " Campo consistemaconta nao Informado.";
       $this->erro_campo = "c60_consistemaconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_identificadorfinanceiro == null ){
       $this->erro_sql = " Campo Identificador financeiro nao Informado.";
       $this->erro_campo = "c60_identificadorfinanceiro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_naturezasaldo == null ){
       $this->erro_sql = " Campo naturezasaldo nao Informado.";
       $this->erro_campo = "c60_naturezasaldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c60_codcon == "" || $c60_codcon == null ){
       $result = db_query("select nextval('conplanoorcamento_c60_codcon_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanoorcamento_c60_codcon_seq do campo: c60_codcon";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c60_codcon = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conplanoorcamento_c60_codcon_seq");
       if(($result != false) && (pg_result($result,0,0) < $c60_codcon)){
         $this->erro_sql = " Campo c60_codcon maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c60_codcon = $c60_codcon;
       }
     }
     if(($this->c60_codcon == null) || ($this->c60_codcon == "") ){
       $this->erro_sql = " Campo c60_codcon nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c60_anousu == null) || ($this->c60_anousu == "") ){
       $this->erro_sql = " Campo c60_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoorcamento(
                                       c60_codcon
                                      ,c60_anousu
                                      ,c60_estrut
                                      ,c60_descr
                                      ,c60_finali
                                      ,c60_codsis
                                      ,c60_codcla
                                      ,c60_consistemaconta
                                      ,c60_identificadorfinanceiro
                                      ,c60_naturezasaldo
                                      ,c60_funcao
                       )
                values (
                                $this->c60_codcon
                               ,$this->c60_anousu
                               ,'$this->c60_estrut'
                               ,'$this->c60_descr'
                               ,'$this->c60_finali'
                               ,$this->c60_codsis
                               ,$this->c60_codcla
                               ,$this->c60_consistemaconta
                               ,'$this->c60_identificadorfinanceiro'
                               ,$this->c60_naturezasaldo
                               ,'$this->c60_funcao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela cópia da conplano ($this->c60_codcon."-".$this->c60_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela cópia da conplano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela cópia da conplano ($this->c60_codcon."-".$this->c60_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c60_codcon,$this->c60_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5220,'$this->c60_codcon','I')");
       $resac = db_query("insert into db_acountkey values($acount,8059,'$this->c60_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,3268,5220,'','".AddSlashes(pg_result($resaco,0,'c60_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,8059,'','".AddSlashes(pg_result($resaco,0,'c60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,5221,'','".AddSlashes(pg_result($resaco,0,'c60_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,5222,'','".AddSlashes(pg_result($resaco,0,'c60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,5223,'','".AddSlashes(pg_result($resaco,0,'c60_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,5224,'','".AddSlashes(pg_result($resaco,0,'c60_codsis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,5225,'','".AddSlashes(pg_result($resaco,0,'c60_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,18504,'','".AddSlashes(pg_result($resaco,0,'c60_consistemaconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,18505,'','".AddSlashes(pg_result($resaco,0,'c60_identificadorfinanceiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,18506,'','".AddSlashes(pg_result($resaco,0,'c60_naturezasaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3268,18534,'','".AddSlashes(pg_result($resaco,0,'c60_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c60_codcon=null,$c60_anousu=null) {
      $this->atualizacampos();
     $sql = " update conplanoorcamento set ";
     $virgula = "";
     if(trim($this->c60_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcon"])){
       $sql  .= $virgula." c60_codcon = $this->c60_codcon ";
       $virgula = ",";
       if(trim($this->c60_codcon) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c60_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_anousu"])){
       $sql  .= $virgula." c60_anousu = $this->c60_anousu ";
       $virgula = ",";
       if(trim($this->c60_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c60_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_estrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_estrut"])){
       $sql  .= $virgula." c60_estrut = '$this->c60_estrut' ";
       $virgula = ",";
       if(trim($this->c60_estrut) == null ){
         $this->erro_sql = " Campo Estrutural nao Informado.";
         $this->erro_campo = "c60_estrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_descr"])){
       $sql  .= $virgula." c60_descr = '$this->c60_descr' ";
       $virgula = ",";
       if(trim($this->c60_descr) == null ){
         $this->erro_sql = " Campo Descrição da conta nao Informado.";
         $this->erro_campo = "c60_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_finali"])){
       $sql  .= $virgula." c60_finali = '$this->c60_finali' ";
       $virgula = ",";
     }
     if(trim($this->c60_codsis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codsis"])){
       $sql  .= $virgula." c60_codsis = $this->c60_codsis ";
       $virgula = ",";
       if(trim($this->c60_codsis) == null ){
         $this->erro_sql = " Campo Sistema nao Informado.";
         $this->erro_campo = "c60_codsis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcla"])){
       $sql  .= $virgula." c60_codcla = $this->c60_codcla ";
       $virgula = ",";
       if(trim($this->c60_codcla) == null ){
         $this->erro_sql = " Campo Classificação nao Informado.";
         $this->erro_campo = "c60_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_consistemaconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"])){
       $sql  .= $virgula." c60_consistemaconta = $this->c60_consistemaconta ";
       $virgula = ",";
       if(trim($this->c60_consistemaconta) == null ){
         $this->erro_sql = " Campo consistemaconta nao Informado.";
         $this->erro_campo = "c60_consistemaconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_identificadorfinanceiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"])){
       $sql  .= $virgula." c60_identificadorfinanceiro = '$this->c60_identificadorfinanceiro' ";
       $virgula = ",";
       if(trim($this->c60_identificadorfinanceiro) == null ){
         $this->erro_sql = " Campo Identificador financeiro nao Informado.";
         $this->erro_campo = "c60_identificadorfinanceiro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_naturezasaldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"])){
       $sql  .= $virgula." c60_naturezasaldo = $this->c60_naturezasaldo ";
       $virgula = ",";
       if(trim($this->c60_naturezasaldo) == null ){
         $this->erro_sql = " Campo naturezasaldo nao Informado.";
         $this->erro_campo = "c60_naturezasaldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_funcao"])){
       $sql  .= $virgula." c60_funcao = '$this->c60_funcao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c60_codcon!=null){
       $sql .= " c60_codcon = $this->c60_codcon";
     }
     if($c60_anousu!=null){
       $sql .= " and  c60_anousu = $this->c60_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c60_codcon,$this->c60_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5220,'$this->c60_codcon','A')");
         $resac = db_query("insert into db_acountkey values($acount,8059,'$this->c60_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_codcon"]) || $this->c60_codcon != "")
           $resac = db_query("insert into db_acount values($acount,3268,5220,'".AddSlashes(pg_result($resaco,$conresaco,'c60_codcon'))."','$this->c60_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_anousu"]) || $this->c60_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3268,8059,'".AddSlashes(pg_result($resaco,$conresaco,'c60_anousu'))."','$this->c60_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_estrut"]) || $this->c60_estrut != "")
           $resac = db_query("insert into db_acount values($acount,3268,5221,'".AddSlashes(pg_result($resaco,$conresaco,'c60_estrut'))."','$this->c60_estrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_descr"]) || $this->c60_descr != "")
           $resac = db_query("insert into db_acount values($acount,3268,5222,'".AddSlashes(pg_result($resaco,$conresaco,'c60_descr'))."','$this->c60_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_finali"]) || $this->c60_finali != "")
           $resac = db_query("insert into db_acount values($acount,3268,5223,'".AddSlashes(pg_result($resaco,$conresaco,'c60_finali'))."','$this->c60_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_codsis"]) || $this->c60_codsis != "")
           $resac = db_query("insert into db_acount values($acount,3268,5224,'".AddSlashes(pg_result($resaco,$conresaco,'c60_codsis'))."','$this->c60_codsis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_codcla"]) || $this->c60_codcla != "")
           $resac = db_query("insert into db_acount values($acount,3268,5225,'".AddSlashes(pg_result($resaco,$conresaco,'c60_codcla'))."','$this->c60_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"]) || $this->c60_consistemaconta != "")
           $resac = db_query("insert into db_acount values($acount,3268,18504,'".AddSlashes(pg_result($resaco,$conresaco,'c60_consistemaconta'))."','$this->c60_consistemaconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"]) || $this->c60_identificadorfinanceiro != "")
           $resac = db_query("insert into db_acount values($acount,3268,18505,'".AddSlashes(pg_result($resaco,$conresaco,'c60_identificadorfinanceiro'))."','$this->c60_identificadorfinanceiro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"]) || $this->c60_naturezasaldo != "")
           $resac = db_query("insert into db_acount values($acount,3268,18506,'".AddSlashes(pg_result($resaco,$conresaco,'c60_naturezasaldo'))."','$this->c60_naturezasaldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c60_funcao"]) || $this->c60_funcao != "")
           $resac = db_query("insert into db_acount values($acount,3268,18534,'".AddSlashes(pg_result($resaco,$conresaco,'c60_funcao'))."','$this->c60_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela cópia da conplano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela cópia da conplano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c60_codcon=null,$c60_anousu=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c60_codcon,$c60_anousu));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5220,'$c60_codcon','E')");
         $resac = db_query("insert into db_acountkey values($acount,8059,'$c60_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,3268,5220,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,8059,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,5221,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,5222,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,5223,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,5224,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_codsis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,5225,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,18504,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_consistemaconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,18505,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_identificadorfinanceiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,18506,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_naturezasaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3268,18534,'','".AddSlashes(pg_result($resaco,$iresaco,'c60_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoorcamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c60_codcon != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c60_codcon = $c60_codcon ";
        }
        if($c60_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c60_anousu = $c60_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela cópia da conplano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela cópia da conplano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoorcamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c60_codcon=null,$c60_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoorcamento ";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplanoorcamento.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplanoorcamento.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplanoorcamento.c60_consistemaconta";
     $sql2 = "";
     if($dbwhere==""){
       if($c60_codcon!=null ){
         $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon ";
       }
       if($c60_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " conplanoorcamento.c60_anousu = $c60_anousu ";
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
   function sql_query_file ( $c60_codcon=null,$c60_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoorcamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($c60_codcon!=null ){
         $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon ";
       }
       if($c60_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " conplanoorcamento.c60_anousu = $c60_anousu ";
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
   *
   * Busca o Plano PCASP
   * @return string
   */
  function sql_query_dados_plano ( $c60_anousu=null, $campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from conplanoorcamento                                                                                           ";
    $sql .= "      left join conplanoorcamentoanalitica     on conplanoorcamento.c60_codcon         = conplanoorcamentoanalitica.c61_codcon      ";
    $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentoanalitica.c61_anousu      ";
    $sql .= "      left join conplanoorcamentoconta         on conplanoorcamento.c60_codcon         = conplanoorcamentoconta.c63_codcon          ";
    $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentoconta.c63_anousu          ";
    $sql .= "      left join conplanoorcamentocontabancaria on conplanoorcamento.c60_codcon         = conplanoorcamentocontabancaria.c56_codcon  ";
    $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentocontabancaria.c56_anousu  ";
    $sql .= "      inner join conclass                      on conplanoorcamento.c60_codcla         = conclass.c51_codcla                        ";
    $sql2 = "";
    if($dbwhere==""){
      if($c60_anousu!=null ){
        $sql2 .= " where conplanoorcamento.c60_anousu = $c60_anousu ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }

    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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
   function sql_query_geral ( $c60_codcon=null,$c60_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoorcamento ";
    $sql .= "      inner join conclass                   on  conclass.c51_codcla = conplanoorcamento.c60_codcla";
    $sql .= "      inner join consistema                 on  consistema.c52_codsis = conplanoorcamento.c60_codsis";
    $sql .= "      left join conplanoorcamentoanalitica  on conplanoorcamentoanalitica.c61_codcon = conplanoorcamento.c60_codcon";
    $sql .= "      																			and conplanoorcamentoanalitica.c61_anousu = c60_anousu";
    $sql2 = "";
    if($dbwhere==""){
      if($c60_codcon!=null && $c60_anousu!=null){
        $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon and conplanoorcamento.c60_anousu=".$c60_anousu;
      } else {
        $sql2 .= " where conplanoorcamento.c60_anousu=".db_getsession("DB_anousu");
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere  ";
    }
    $x      = @db_query("select prefeitura from db_config where codigo=".db_getsession("DB_instit"));
    $libera = @pg_result($x,0,0);
    $dbw = '';
    if($libera == "t"){
      //$dbw = " c61_instit is null or ";
    } else {
      $sql2 .= ($sql2!=""?" and ":" where ") . " ( $dbw ( c61_instit is not null and c61_instit = " . db_getsession("DB_instit")." ))";
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
  *
  * Busca inconsistencia no Plano Orcamentário
  * @return string
  */
  function sql_query_inconsistencia_plano ( $c60_anousu=null, $campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from conplanoorcamento																									                                              ";
    $sql .= "left join conplanoorcamentoanalitica on conplanoorcamentoanalitica.c61_anousu            = conplanoorcamento.c60_anousu ";
    $sql .= "                                    and conplanoorcamentoanalitica.c61_codcon            = conplanoorcamento.c60_codcon ";
    $sql .= "left join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_anousu             = conplanoorcamento.c60_anousu ";
    $sql .= "                                    and conplanoconplanoorcamento.c72_conplanoorcamento  = conplanoorcamento.c60_codcon ";
    $sql2 = "";
    if($dbwhere==""){
      if($c60_anousu!=null ){
        $sql2 .= " where conplanoorcamento.c60_anousu = $c60_anousu ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }

    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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
  *
  * Busca o Plano Orcamentário
  * @return string
  */
  function sql_query_plano_orcamentario ( $c60_anousu=null, $campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from conplanoorcamento																									                                         ";
    $sql .= "      left join conplanoorcamentoanalitica     on conplano.c60_codcon = conplanoorcamentoanalitica.c61_codcon     ";
    $sql .= "                                              and conplano.c60_anousu = conplanoorcamentoanalitica.c61_anousu     ";
    $sql .= "      left join conplanoorcamentoconta         on conplano.c60_codcon = conplanoorcamentoconta.c63_codcon         ";
    $sql .= "                                     		     and conplano.c60_anousu = conplanoorcamentoconta.c63_anousu         ";
    $sql .= "      left join conplanoorcamentocontabancaria on conplano.c60_codcon = conplanoorcamentocontabancaria.c56_codcon ";
    $sql .= "                                              and conplano.c60_anousu = conplanoorcamentocontabancaria.c56_anousu ";
    $sql .= "      inner join conclass   	                  on conplano.c60_codcla         = conclass.c51_codcla 							 ";
    $sql2 = "";
    if($dbwhere==""){
      if($c60_anousu!=null ){
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }

    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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

  function db_verifica_conplano($conplano,$anousu){

    $nivel = db_le_mae_conplano($conplano,true);
    if($nivel == 1){
      return true;
    }

    $cod_mae = db_le_mae_conplano($conplano,false);
    $this->sql_record($this->sql_query_file("","","c60_estrut",""," c60_anousu=$anousu and  c60_estrut='$cod_mae'"));

    if($this->numrows<1){

      $this->erro_msg = 'Procedimento abortado. Estrutural acima não encontrado!';
      return false;
    }

    $this->erro_msg = 'Conplano válido!';
    return true;
  }
}
?>