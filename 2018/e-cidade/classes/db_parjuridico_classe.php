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

//MODULO: juridico
//CLASSE DA ENTIDADE parjuridico
class cl_parjuridico {
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
   var $v19_anousu = 0;
   var $v19_instit = 0;
   var $v19_envolinicialiptu = 0;
   var $v19_envolinicialiss = 0;
   var $v19_envolprinciptu = 'f';
   var $v19_vlrexecmin = 0;
   var $v19_partilha = 'f';
   var $v19_templateinicialquitada = 0;
   var $v19_templateparcelamento = 0;
   var $v19_urlwebservice = null;
   var $v19_login = null;
   var $v19_senha = null;
   var $v19_codorgao = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v19_anousu = int4 = Ano
                 v19_instit = int4 = Instituição
                 v19_envolinicialiptu = int4 = Envolvidos na Inicial (Imóvel)
                 v19_envolinicialiss = int4 = Envolvidos na Inicial (Empresa)
                 v19_envolprinciptu = bool = Somente Principais (Imóvel)
                 v19_vlrexecmin = float4 = Valor Mínimo para Execução
                 v19_partilha = bool = Partilha
                 v19_templateinicialquitada = int4 = Documento Template
                 v19_templateparcelamento = int4 = Documento Template
                 v19_urlwebservice = varchar(50) = Url WebService
                 v19_login = varchar(20) = Login
                 v19_senha = varchar(20) = Senha
                 v19_codorgao = int4 = Código Orgão
                 ";
   //funcao construtor da classe
   function cl_parjuridico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parjuridico");
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
       $this->v19_anousu = ($this->v19_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_anousu"]:$this->v19_anousu);
       $this->v19_instit = ($this->v19_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_instit"]:$this->v19_instit);
       $this->v19_envolinicialiptu = ($this->v19_envolinicialiptu == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiptu"]:$this->v19_envolinicialiptu);
       $this->v19_envolinicialiss = ($this->v19_envolinicialiss == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiss"]:$this->v19_envolinicialiss);
       $this->v19_envolprinciptu = ($this->v19_envolprinciptu == "f"?@$GLOBALS["HTTP_POST_VARS"]["v19_envolprinciptu"]:$this->v19_envolprinciptu);
       $this->v19_vlrexecmin = ($this->v19_vlrexecmin == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_vlrexecmin"]:$this->v19_vlrexecmin);
       $this->v19_partilha = ($this->v19_partilha == "f"?@$GLOBALS["HTTP_POST_VARS"]["v19_partilha"]:$this->v19_partilha);
       $this->v19_templateinicialquitada = ($this->v19_templateinicialquitada == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"]:$this->v19_templateinicialquitada);
       $this->v19_templateparcelamento = ($this->v19_templateparcelamento == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"]:$this->v19_templateparcelamento);
       $this->v19_urlwebservice = ($this->v19_urlwebservice == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_urlwebservice"]:$this->v19_urlwebservice);
       $this->v19_login = ($this->v19_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_login"]:$this->v19_login);
       $this->v19_senha = ($this->v19_senha == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_senha"]:$this->v19_senha);
       $this->v19_codorgao = ($this->v19_codorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_codorgao"]:$this->v19_codorgao);
     }else{
       $this->v19_anousu = ($this->v19_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_anousu"]:$this->v19_anousu);
       $this->v19_instit = ($this->v19_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v19_instit"]:$this->v19_instit);
     }
   }
   // funcao para inclusao
   function incluir ($v19_anousu,$v19_instit){
      $this->atualizacampos();
     if($this->v19_envolinicialiptu == null ){
       $this->erro_sql = " Campo Envolvidos na Inicial (Imóvel) nao Informado.";
       $this->erro_campo = "v19_envolinicialiptu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v19_envolinicialiss == null ){
       $this->erro_sql = " Campo Envolvidos na Inicial (Empresa) nao Informado.";
       $this->erro_campo = "v19_envolinicialiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v19_envolprinciptu == null ){
       $this->erro_sql = " Campo Somente Principais (Imóvel) nao Informado.";
       $this->erro_campo = "v19_envolprinciptu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v19_vlrexecmin == null ){
       $this->v19_vlrexecmin = 'null';
     }
     if($this->v19_partilha == null ){
       $this->erro_sql = " Campo Partilha nao Informado.";
       $this->erro_campo = "v19_partilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v19_templateinicialquitada == null ){
       $this->v19_templateinicialquitada = 'null';
     }
     if($this->v19_templateparcelamento == null ){
       $this->v19_templateparcelamento = 'null';
     }

     $this->v19_anousu = $v19_anousu;
     $this->v19_instit = $v19_instit;

     if(($this->v19_anousu == null) || ($this->v19_anousu == "") ){
       $this->erro_sql = " Campo v19_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->v19_instit == null) || ($this->v19_instit == "") ){
       $this->erro_sql = " Campo v19_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->v19_partilha == 't' ){

       if($this->v19_urlwebservice == null || ($this->v19_urlwebservice == "") ){
        $this->erro_sql = " Campo Url WebService nao Informado.";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
       }

       if($this->v19_login == null || ($this->v19_login == "") ){
        $this->erro_sql = " Campo Login nao Informado.";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
       }

       if($this->v19_senha == null || ($this->v19_senha == "") ){
        $this->erro_sql = " Campo Senha nao Informado.";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
       }
       if($this->v19_codorgao == null || ($this->v19_codorgao == "") ){
        $this->erro_sql = " Campo Código Orgão nao Informado.";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
       }
     }
     $sql = "insert into parjuridico(
                                       v19_anousu
                                      ,v19_instit
                                      ,v19_envolinicialiptu
                                      ,v19_envolinicialiss
                                      ,v19_envolprinciptu
                                      ,v19_vlrexecmin
                                      ,v19_partilha
                                      ,v19_templateinicialquitada
                                      ,v19_templateparcelamento
                                      ,v19_urlwebservice
                                      ,v19_login
                                      ,v19_senha
                                      ,v19_codorgao
                       )
                values (
                                $this->v19_anousu
                               ,$this->v19_instit
                               ,$this->v19_envolinicialiptu
                               ,$this->v19_envolinicialiss
                               ,'$this->v19_envolprinciptu'
                               ,$this->v19_vlrexecmin
                               ,'$this->v19_partilha'
                               ,$this->v19_templateinicialquitada
                               ,$this->v19_templateparcelamento
                               ,'$this->v19_urlwebservice'
                               ,'$this->v19_login'
                               ,'$this->v19_senha'
                               ,'$this->v19_codorgao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "parjuridico ($this->v19_anousu."-".$this->v19_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "parjuridico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "parjuridico ($this->v19_anousu."-".$this->v19_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v19_anousu,$this->v19_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11762,'$this->v19_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,11763,'$this->v19_instit','I')");
       $resac = db_query("insert into db_acount values($acount,2029,11762,'','".AddSlashes(pg_result($resaco,0,'v19_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,11763,'','".AddSlashes(pg_result($resaco,0,'v19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,11764,'','".AddSlashes(pg_result($resaco,0,'v19_envolinicialiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,11765,'','".AddSlashes(pg_result($resaco,0,'v19_envolinicialiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,11766,'','".AddSlashes(pg_result($resaco,0,'v19_envolprinciptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,18156,'','".AddSlashes(pg_result($resaco,0,'v19_vlrexecmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,18234,'','".AddSlashes(pg_result($resaco,0,'v19_partilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,18811,'','".AddSlashes(pg_result($resaco,0,'v19_templateinicialquitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,18812,'','".AddSlashes(pg_result($resaco,0,'v19_templateparcelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,19708,'','".AddSlashes(pg_result($resaco,0,'v19_urlwebservice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,19709,'','".AddSlashes(pg_result($resaco,0,'v19_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,19710,'','".AddSlashes(pg_result($resaco,0,'v19_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2029,19711,'','".AddSlashes(pg_result($resaco,0,'v19_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v19_anousu=null,$v19_instit=null) {
      $this->atualizacampos();
     $sql = " update parjuridico set ";
     $virgula = "";
     if(trim($this->v19_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_anousu"])){
       $sql  .= $virgula." v19_anousu = $this->v19_anousu ";
       $virgula = ",";
       if(trim($this->v19_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "v19_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_instit"])){
       $sql  .= $virgula." v19_instit = $this->v19_instit ";
       $virgula = ",";
       if(trim($this->v19_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "v19_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_envolinicialiptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiptu"])){
       $sql  .= $virgula." v19_envolinicialiptu = $this->v19_envolinicialiptu ";
       $virgula = ",";
       if(trim($this->v19_envolinicialiptu) == null ){
         $this->erro_sql = " Campo Envolvidos na Inicial (Imóvel) nao Informado.";
         $this->erro_campo = "v19_envolinicialiptu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_envolinicialiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiss"])){
       $sql  .= $virgula." v19_envolinicialiss = $this->v19_envolinicialiss ";
       $virgula = ",";
       if(trim($this->v19_envolinicialiss) == null ){
         $this->erro_sql = " Campo Envolvidos na Inicial (Empresa) nao Informado.";
         $this->erro_campo = "v19_envolinicialiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_envolprinciptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolprinciptu"])){
       $sql  .= $virgula." v19_envolprinciptu = '$this->v19_envolprinciptu' ";
       $virgula = ",";
       if(trim($this->v19_envolprinciptu) == null ){
         $this->erro_sql = " Campo Somente Principais (Imóvel) nao Informado.";
         $this->erro_campo = "v19_envolprinciptu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_vlrexecmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_vlrexecmin"])){
       $sql  .= $virgula." v19_vlrexecmin = $this->v19_vlrexecmin ";
       $virgula = ",";
       if(trim($this->v19_vlrexecmin) == null ){
         $this->erro_sql = " Campo Valor Mínimo para Execução nao Informado.";
         $this->erro_campo = "v19_vlrexecmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_partilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_partilha"])){
       $sql  .= $virgula." v19_partilha = '$this->v19_partilha' ";
       $virgula = ",";
       if(trim($this->v19_partilha) == null ){
         $this->erro_sql = " Campo Partilha nao Informado.";
         $this->erro_campo = "v19_partilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v19_templateinicialquitada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"])){
        if(trim($this->v19_templateinicialquitada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"])){
           $this->v19_templateinicialquitada = "0" ;
        }
       $sql  .= $virgula." v19_templateinicialquitada = $this->v19_templateinicialquitada ";
       $virgula = ",";
     }
     if(trim($this->v19_templateparcelamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"])){
        if(trim($this->v19_templateparcelamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"])){
           $this->v19_templateparcelamento = "0" ;
        }
       $sql  .= $virgula." v19_templateparcelamento = $this->v19_templateparcelamento ";
       $virgula = ",";
     }
     if(trim($this->v19_urlwebservice)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_urlwebservice"])){
      $sql  .= $virgula." v19_urlwebservice = '$this->v19_urlwebservice' ";
      $virgula = ",";
      if(trim($this->v19_urlwebservice) == null ){
       $this->erro_sql = " Campo Url WebService nao Informado.";
       $this->erro_campo = "v19_urlwebservice";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
      }
     }
     if(trim($this->v19_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_login"])){
      $sql  .= $virgula." v19_login = '$this->v19_login' ";
      $virgula = ",";
      if(trim($this->v19_login) == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "v19_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
      }
     }
     if(trim($this->v19_senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_senha"])){
      $sql  .= $virgula." v19_senha = '$this->v19_senha' ";
      $virgula = ",";
      if(trim($this->v19_senha) == null ){
       $this->erro_sql = " Campo Senha nao Informado.";
       $this->erro_campo = "v19_senha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
      }
     }
     if(trim($this->v19_codorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"])){
      if(trim($this->v19_codorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"])){
       $this->v19_codorgao = "0" ;
      }
      $sql  .= $virgula." v19_codorgao = '$this->v19_codorgao' ";
      $virgula = ",";
      if(trim($this->v19_codorgao) == null ){
       $this->erro_sql = " Campo Código Orgão nao Informado.";
       $this->erro_campo = "v19_codorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
      }
     }
     $sql .= " where ";
     if($v19_anousu!=null){
       $sql .= " v19_anousu = $this->v19_anousu";
     }
     if($v19_instit!=null){
       $sql .= " and  v19_instit = $this->v19_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v19_anousu,$this->v19_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11762,'$this->v19_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,11763,'$this->v19_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_anousu"]) || $this->v19_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2029,11762,'".AddSlashes(pg_result($resaco,$conresaco,'v19_anousu'))."','$this->v19_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_instit"]) || $this->v19_instit != "")
           $resac = db_query("insert into db_acount values($acount,2029,11763,'".AddSlashes(pg_result($resaco,$conresaco,'v19_instit'))."','$this->v19_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiptu"]) || $this->v19_envolinicialiptu != "")
           $resac = db_query("insert into db_acount values($acount,2029,11764,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolinicialiptu'))."','$this->v19_envolinicialiptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiss"]) || $this->v19_envolinicialiss != "")
           $resac = db_query("insert into db_acount values($acount,2029,11765,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolinicialiss'))."','$this->v19_envolinicialiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolprinciptu"]) || $this->v19_envolprinciptu != "")
           $resac = db_query("insert into db_acount values($acount,2029,11766,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolprinciptu'))."','$this->v19_envolprinciptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_vlrexecmin"]) || $this->v19_vlrexecmin != "")
           $resac = db_query("insert into db_acount values($acount,2029,18156,'".AddSlashes(pg_result($resaco,$conresaco,'v19_vlrexecmin'))."','$this->v19_vlrexecmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_partilha"]) || $this->v19_partilha != "")
           $resac = db_query("insert into db_acount values($acount,2029,18234,'".AddSlashes(pg_result($resaco,$conresaco,'v19_partilha'))."','$this->v19_partilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"]) || $this->v19_templateinicialquitada != "")
           $resac = db_query("insert into db_acount values($acount,2029,18811,'".AddSlashes(pg_result($resaco,$conresaco,'v19_templateinicialquitada'))."','$this->v19_templateinicialquitada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"]) || $this->v19_templateparcelamento != "")
           $resac = db_query("insert into db_acount values($acount,2029,18812,'".AddSlashes(pg_result($resaco,$conresaco,'v19_templateparcelamento'))."','$this->v19_templateparcelamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_urlwebservice"]) || $this->v19_urlwebservice != "")
          $resac = db_query("insert into db_acount values($acount,2029,19708,'".AddSlashes(pg_result($resaco,$conresaco,'v19_urlwebservice'))."','$this->v19_urlwebservice',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_login"]) || $this->v19_login != "")
          $resac = db_query("insert into db_acount values($acount,2029,19709,'".AddSlashes(pg_result($resaco,$conresaco,'v19_login'))."','$this->v19_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_senha"]) || $this->v19_senha != "")
          $resac = db_query("insert into db_acount values($acount,2029,19710,'".AddSlashes(pg_result($resaco,$conresaco,'v19_senha'))."','$this->v19_senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"]) || $this->v19_codorgao != "")
          $resac = db_query("insert into db_acount values($acount,2029,19711,'".AddSlashes(pg_result($resaco,$conresaco,'v19_codorgao'))."','$this->v19_codorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parjuridico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parjuridico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v19_anousu=null,$v19_instit=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v19_anousu,$v19_instit));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11762,'$v19_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,11763,'$v19_instit','E')");
         $resac = db_query("insert into db_acount values($acount,2029,11762,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,11763,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,11764,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_envolinicialiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,11765,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_envolinicialiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,11766,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_envolprinciptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,18156,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_vlrexecmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,18234,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_partilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,18811,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_templateinicialquitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,18812,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_templateparcelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,19708,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_urlwebservice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,19709,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,19710,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2029,19711,'','".AddSlashes(pg_result($resaco,$iresaco,'v19_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from parjuridico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v19_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v19_anousu = $v19_anousu ";
        }
        if($v19_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v19_instit = $v19_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parjuridico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v19_anousu."-".$v19_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parjuridico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v19_anousu."-".$v19_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v19_anousu."-".$v19_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:parjuridico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $v19_anousu=null,$v19_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from parjuridico ";
     $sql .= "      inner join db_config  on  db_config.codigo = parjuridico.v19_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($v19_anousu!=null ){
         $sql2 .= " where parjuridico.v19_anousu = $v19_anousu ";
       }
       if($v19_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " parjuridico.v19_instit = $v19_instit ";
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
   function sql_query_file ( $v19_anousu=null,$v19_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from parjuridico ";
     $sql2 = "";
     if($dbwhere==""){
       if($v19_anousu!=null ){
         $sql2 .= " where parjuridico.v19_anousu = $v19_anousu ";
       }
       if($v19_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " parjuridico.v19_instit = $v19_instit ";
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
   * Método alternativo ao sql_query
   * @param integer $v19_anousu
   * @param inetger $v19_instit
   * @param string  $campos
   * @param string  $ordem
   * @param string  $dbwhere
   */
  function sql_query_alternativo( $v19_anousu=null,$v19_instit=null,$campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from parjuridico                                                                                               ";
    $sql .= "      inner join db_config              on  db_config.codigo           = parjuridico.v19_instit                 ";
    $sql .= "      left  join db_documentotemplate a on  a.db82_sequencial          = parjuridico.v19_templateinicialquitada ";
    $sql .= "      left  join db_documentotemplate b on  b.db82_sequencial          = parjuridico.v19_templateparcelamento   ";
    $sql .= "      inner join cgm                    on  cgm.z01_numcgm             = db_config.numcgm                       ";
    $sql .= "      inner join db_tipoinstit          on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit              ";
    $sql2 = "";
    if($dbwhere==""){
      if($v19_anousu!=null ){
        $sql2 .= " where parjuridico.v19_anousu = $v19_anousu ";
      }
      if($v19_instit!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " parjuridico.v19_instit = $v19_instit ";
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
   * Método alterar que é possível alterar campos preenchidos para nulo
   * @param integer $v19_anousu
   * @param integre $v19_instit
   * @return boolean
   */
  function alterar_camposNulos($v19_anousu=null,$v19_instit=null) {
    $this->atualizacampos();
    $sql = " update parjuridico set ";
    $virgula = "";
    if(trim($this->v19_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_anousu"])){
      $sql  .= $virgula." v19_anousu = $this->v19_anousu ";
      $virgula = ",";
      if(trim($this->v19_anousu) == null ){
        $this->erro_sql = " Campo Ano nao Informado.";
        $this->erro_campo = "v19_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_instit"])){
      $sql  .= $virgula." v19_instit = $this->v19_instit ";
      $virgula = ",";
      if(trim($this->v19_instit) == null ){
        $this->erro_sql = " Campo Instituição nao Informado.";
        $this->erro_campo = "v19_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_envolinicialiptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiptu"])){
      $sql  .= $virgula." v19_envolinicialiptu = $this->v19_envolinicialiptu ";
      $virgula = ",";
      if(trim($this->v19_envolinicialiptu) == null ){
        $this->erro_sql = " Campo Envolvidos na Inicial (Imóvel) nao Informado.";
        $this->erro_campo = "v19_envolinicialiptu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_envolinicialiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiss"])){
      $sql  .= $virgula." v19_envolinicialiss = $this->v19_envolinicialiss ";
      $virgula = ",";
      if(trim($this->v19_envolinicialiss) == null ){
        $this->erro_sql = " Campo Envolvidos na Inicial (Empresa) nao Informado.";
        $this->erro_campo = "v19_envolinicialiss";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_envolprinciptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_envolprinciptu"])){
      $sql  .= $virgula." v19_envolprinciptu = '$this->v19_envolprinciptu' ";
      $virgula = ",";
      if(trim($this->v19_envolprinciptu) == null ){
        $this->erro_sql = " Campo Somente Principais (Imóvel) nao Informado.";
        $this->erro_campo = "v19_envolprinciptu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_vlrexecmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_vlrexecmin"])){
      if(trim($this->v19_vlrexecmin) == null ){
        $this->v19_vlrexecmin = 'null';
      }
      $sql  .= $virgula." v19_vlrexecmin = $this->v19_vlrexecmin ";
      $virgula = ",";
    }
    if(trim($this->v19_partilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_partilha"])){
      $sql  .= $virgula." v19_partilha = '$this->v19_partilha' ";
      $virgula = ",";
      if(trim($this->v19_partilha) == null ){
        $this->erro_sql = " Campo Partilha nao Informado.";
        $this->erro_campo = "v19_partilha";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_templateinicialquitada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"])){
      if(trim($this->v19_templateinicialquitada)==""){
        $this->v19_templateinicialquitada = "null" ;
      }
      $sql  .= $virgula." v19_templateinicialquitada = $this->v19_templateinicialquitada ";
      $virgula = ",";
    }
    if(trim($this->v19_templateparcelamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"])){
      if(trim($this->v19_templateparcelamento)==""){
        $this->v19_templateparcelamento = "null" ;
      }
      $sql  .= $virgula." v19_templateparcelamento = $this->v19_templateparcelamento ";
      $virgula = ",";
    }
    if(trim($this->v19_urlwebservice)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_urlwebservice"])){

      if (trim($this->v19_urlwebservice) == ""){
        $this->v19_urlwebservice = "" ;
      }
      $sql  .= $virgula." v19_urlwebservice = '$this->v19_urlwebservice' ";
      $virgula = ",";

      if ($this->v19_partilha == "t" && trim($this->v19_urlwebservice) == null) {

        $this->erro_sql = " Campo UrlWebService nao Informado.";
        $this->erro_campo = "v19_urlwebservice";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_login"])){
      if(trim($this->v19_login)==""){
        $this->v19_login = "" ;
      }
      $sql  .= $virgula." v19_login = '$this->v19_login' ";
      $virgula = ",";

      if ($this->v19_partilha == "t" && trim($this->v19_login) == null) {
        $this->erro_sql = " Campo Login nao Informado.";
        $this->erro_campo = "v19_login";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }

    }
    if(trim($this->v19_senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_senha"])){
      if(trim($this->v19_senha)==""){
        $this->v19_senha = "" ;
      }
      $sql  .= $virgula." v19_senha = '$this->v19_senha' ";
      $virgula = ",";

      if ($this->v19_partilha == "t" && trim($this->v19_senha) == null) {
        $this->erro_sql = " Campo Senha nao Informado.";
        $this->erro_campo = "v19_senha";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->v19_codorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"])){
     if(trim($this->v19_codorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"])){
      $this->v19_codorgao = "";
     }
     $sql  .= $virgula." v19_codorgao = '$this->v19_codorgao' ";
     $virgula = ",";
     if ($this->v19_partilha == "t" && trim($this->v19_codorgao) == null) {
       $this->erro_sql = " Campo Código Orgão nao Informado.";
       $this->erro_campo = "v19_codorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
    }
    $sql .= " where ";
    if($v19_anousu!=null){
      $sql .= " v19_anousu = $this->v19_anousu";
    }
    if($v19_instit!=null){
      $sql .= " and  v19_instit = $this->v19_instit";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->v19_anousu,$this->v19_instit));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,11762,'$this->v19_anousu','A')");
        $resac = db_query("insert into db_acountkey values($acount,11763,'$this->v19_instit','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_anousu"]) || $this->v19_anousu != "")
          $resac = db_query("insert into db_acount values($acount,2029,11762,'".AddSlashes(pg_result($resaco,$conresaco,'v19_anousu'))."','$this->v19_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_instit"]) || $this->v19_instit != "")
          $resac = db_query("insert into db_acount values($acount,2029,11763,'".AddSlashes(pg_result($resaco,$conresaco,'v19_instit'))."','$this->v19_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiptu"]) || $this->v19_envolinicialiptu != "")
          $resac = db_query("insert into db_acount values($acount,2029,11764,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolinicialiptu'))."','$this->v19_envolinicialiptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolinicialiss"]) || $this->v19_envolinicialiss != "")
          $resac = db_query("insert into db_acount values($acount,2029,11765,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolinicialiss'))."','$this->v19_envolinicialiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_envolprinciptu"]) || $this->v19_envolprinciptu != "")
          $resac = db_query("insert into db_acount values($acount,2029,11766,'".AddSlashes(pg_result($resaco,$conresaco,'v19_envolprinciptu'))."','$this->v19_envolprinciptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_vlrexecmin"]) || $this->v19_vlrexecmin != "")
          $resac = db_query("insert into db_acount values($acount,2029,18156,'".AddSlashes(pg_result($resaco,$conresaco,'v19_vlrexecmin'))."','$this->v19_vlrexecmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_partilha"]) || $this->v19_partilha != "")
          $resac = db_query("insert into db_acount values($acount,2029,18234,'".AddSlashes(pg_result($resaco,$conresaco,'v19_partilha'))."','$this->v19_partilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_templateinicialquitada"]) || $this->v19_templateinicialquitada != "")
          $resac = db_query("insert into db_acount values($acount,2029,18811,'".AddSlashes(pg_result($resaco,$conresaco,'v19_templateinicialquitada'))."','$this->v19_templateinicialquitada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_templateparcelamento"]) || $this->v19_templateparcelamento != "")
          $resac = db_query("insert into db_acount values($acount,2029,18812,'".AddSlashes(pg_result($resaco,$conresaco,'v19_templateparcelamento'))."','$this->v19_templateparcelamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_urlwebservice"]) || $this->v19_urlwebservice != "")
         $resac = db_query("insert into db_acount values($acount,2029,19708,'".AddSlashes(pg_result($resaco,$conresaco,'v19_urlwebservice'))."','$this->v19_urlwebservice',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_login"]) || $this->v19_login != "")
         $resac = db_query("insert into db_acount values($acount,2029,19709,'".AddSlashes(pg_result($resaco,$conresaco,'v19_login'))."','$this->v19_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_senha"]) || $this->v19_senha != "")
         $resac = db_query("insert into db_acount values($acount,2029,19710,'".AddSlashes(pg_result($resaco,$conresaco,'v19_senha'))."','$this->v19_senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["v19_codorgao"]) || $this->v19_codorgao != "")
         $resac = db_query("insert into db_acount values($acount,2029,19711,'".AddSlashes(pg_result($resaco,$conresaco,'v19_codorgao'))."','$this->v19_codorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "parjuridico nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "parjuridico nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->v19_anousu."-".$this->v19_instit;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  /**
   * Retorna array de objetos com os parametros do juridico.
   * @param integer   $iInstit
   * @param integer   $iAnoUsu
   * @return Ambigous <array(), multitype:_db_fields >|boolean
   */
  function getParametrosJuridico($iInstit, $iAnoUsu = null) {

    $sSql = "select * from parjuridico where v19_instit = " . $iInstit;

    if ( !empty($iAnoUsu) ) {
      $sSql .= " and v19_anousu = " . $iAnoUsu;
    }
    $rsSql = db_query($sSql);

    if  ( $rsSql && pg_num_rows($rsSql) ) {
      return db_utils::getCollectionByRecord($rsSql);
    }
    return false;
  }
}

?>