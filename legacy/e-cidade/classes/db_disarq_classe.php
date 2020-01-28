<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE disarq
class cl_disarq {
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
   var $id_usuario = 0;
   var $k15_codbco = 0;
   var $k15_codage = null;
   var $codret = 0;
   var $arqret = null;
   var $textoret = null;
   var $dtretorno_dia = null;
   var $dtretorno_mes = null;
   var $dtretorno_ano = null;
   var $dtretorno = null;
   var $dtarquivo_dia = null;
   var $dtarquivo_mes = null;
   var $dtarquivo_ano = null;
   var $dtarquivo = null;
   var $k00_conta = 0;
   var $autent = 'f';
   var $instit = 0;
   var $md5 = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 id_usuario = int4 = Cod. Usuário
                 k15_codbco = int4 = Banco
                 k15_codage = char(5) = Agência
                 codret = int4 = Código
                 arqret = varchar(100) = Arquivo de retorno
                 textoret = text = Corpo do arquivo retorno
                 dtretorno = date = Data arquivo
                 dtarquivo = date = Data Arquivo
                 k00_conta = int4 = Conta
                 autent = bool = Autentica
                 instit = int4 = Instituição
                 md5 = char(32) = Identificador do arquivo
                 ";
   //funcao construtor da classe
   function cl_disarq() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disarq");
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
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->k15_codbco = ($this->k15_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codbco"]:$this->k15_codbco);
       $this->k15_codage = ($this->k15_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codage"]:$this->k15_codage);
       $this->codret = ($this->codret == ""?@$GLOBALS["HTTP_POST_VARS"]["codret"]:$this->codret);
       $this->arqret = ($this->arqret == ""?@$GLOBALS["HTTP_POST_VARS"]["arqret"]:$this->arqret);
       $this->textoret = ($this->textoret == ""?@$GLOBALS["HTTP_POST_VARS"]["textoret"]:$this->textoret);
       if($this->dtretorno == ""){
         $this->dtretorno_dia = ($this->dtretorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtretorno_dia"]:$this->dtretorno_dia);
         $this->dtretorno_mes = ($this->dtretorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtretorno_mes"]:$this->dtretorno_mes);
         $this->dtretorno_ano = ($this->dtretorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtretorno_ano"]:$this->dtretorno_ano);
         if($this->dtretorno_dia != ""){
            $this->dtretorno = $this->dtretorno_ano."-".$this->dtretorno_mes."-".$this->dtretorno_dia;
         }
       }
       if($this->dtarquivo == ""){
         $this->dtarquivo_dia = ($this->dtarquivo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarquivo_dia"]:$this->dtarquivo_dia);
         $this->dtarquivo_mes = ($this->dtarquivo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarquivo_mes"]:$this->dtarquivo_mes);
         $this->dtarquivo_ano = ($this->dtarquivo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarquivo_ano"]:$this->dtarquivo_ano);
         if($this->dtarquivo_dia != ""){
            $this->dtarquivo = $this->dtarquivo_ano."-".$this->dtarquivo_mes."-".$this->dtarquivo_dia;
         }
       }
       $this->k00_conta = ($this->k00_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_conta"]:$this->k00_conta);
       $this->autent = ($this->autent == "f"?@$GLOBALS["HTTP_POST_VARS"]["autent"]:$this->autent);
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
       $this->md5 = ($this->md5 == ""?@$GLOBALS["HTTP_POST_VARS"]["md5"]:$this->md5);
     }else{
       $this->codret = ($this->codret == ""?@$GLOBALS["HTTP_POST_VARS"]["codret"]:$this->codret);
     }
   }
   // funcao para inclusao
   function incluir ($codret){
      $this->atualizacampos();
     if($this->id_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_codbco == null ){
       $this->erro_sql = " Campo Banco nao Informado.";
       $this->erro_campo = "k15_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_codage == null ){
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "k15_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->arqret == null ){
       $this->erro_sql = " Campo Arquivo de retorno nao Informado.";
       $this->erro_campo = "arqret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtretorno == null ){
       $this->erro_sql = " Campo Data arquivo nao Informado.";
       $this->erro_campo = "dtretorno_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtarquivo == null ){
       $this->erro_sql = " Campo Data Arquivo nao Informado.";
       $this->erro_campo = "dtarquivo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_conta == null ){
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "k00_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->autent == null ){
       $this->erro_sql = " Campo Autentica nao Informado.";
       $this->erro_campo = "autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codret == "" || $codret == null ){
       $result = db_query("select nextval('disarq_codret_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disarq_codret_seq do campo: codret";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->codret = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from disarq_codret_seq");
       if(($result != false) && (pg_result($result,0,0) < $codret)){
         $this->erro_sql = " Campo codret maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codret = $codret;
       }
     }
     if(($this->codret == null) || ($this->codret == "") ){
       $this->erro_sql = " Campo codret nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disarq(
                                       id_usuario
                                      ,k15_codbco
                                      ,k15_codage
                                      ,codret
                                      ,arqret
                                      ,textoret
                                      ,dtretorno
                                      ,dtarquivo
                                      ,k00_conta
                                      ,autent
                                      ,instit
                                      ,md5
                       )
                values (
                                $this->id_usuario
                               ,$this->k15_codbco
                               ,'$this->k15_codage'
                               ,$this->codret
                               ,'$this->arqret'
                               ,'$this->textoret'
                               ,".($this->dtretorno == "null" || $this->dtretorno == ""?"null":"'".$this->dtretorno."'")."
                               ,".($this->dtarquivo == "null" || $this->dtarquivo == ""?"null":"'".$this->dtarquivo."'")."
                               ,$this->k00_conta
                               ,'$this->autent'
                               ,$this->instit
                               ,'$this->md5'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos de Baixa banco ($this->codret) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos de Baixa banco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos de Baixa banco ($this->codret) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codret;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codret));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1179,'$this->codret','I')");
       $resac = db_query("insert into db_acount values($acount,213,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,586,'','".AddSlashes(pg_result($resaco,0,'k15_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,587,'','".AddSlashes(pg_result($resaco,0,'k15_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,1179,'','".AddSlashes(pg_result($resaco,0,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,1180,'','".AddSlashes(pg_result($resaco,0,'arqret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,1182,'','".AddSlashes(pg_result($resaco,0,'textoret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,1183,'','".AddSlashes(pg_result($resaco,0,'dtretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,1895,'','".AddSlashes(pg_result($resaco,0,'dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,736,'','".AddSlashes(pg_result($resaco,0,'k00_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,4858,'','".AddSlashes(pg_result($resaco,0,'autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,9996,'','".AddSlashes(pg_result($resaco,0,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,213,19304,'','".AddSlashes(pg_result($resaco,0,'md5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($codret=null) {
      $this->atualizacampos();
     $sql = " update disarq set ";
     $virgula = "";
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"])){
       $sql  .= $virgula." k15_codbco = $this->k15_codbco ";
       $virgula = ",";
       if(trim($this->k15_codbco) == null ){
         $this->erro_sql = " Campo Banco nao Informado.";
         $this->erro_campo = "k15_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"])){
       $sql  .= $virgula." k15_codage = '$this->k15_codage' ";
       $virgula = ",";
       if(trim($this->k15_codage) == null ){
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "k15_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codret"])){
       $sql  .= $virgula." codret = $this->codret ";
       $virgula = ",";
       if(trim($this->codret) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->arqret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["arqret"])){
       $sql  .= $virgula." arqret = '$this->arqret' ";
       $virgula = ",";
       if(trim($this->arqret) == null ){
         $this->erro_sql = " Campo Arquivo de retorno nao Informado.";
         $this->erro_campo = "arqret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->textoret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["textoret"])){
       $sql  .= $virgula." textoret = '$this->textoret' ";
       $virgula = ",";
     }
     if(trim($this->dtretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtretorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtretorno_dia"] !="") ){
       $sql  .= $virgula." dtretorno = '$this->dtretorno' ";
       $virgula = ",";
       if(trim($this->dtretorno) == null ){
         $this->erro_sql = " Campo Data arquivo nao Informado.";
         $this->erro_campo = "dtretorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtretorno_dia"])){
         $sql  .= $virgula." dtretorno = null ";
         $virgula = ",";
         if(trim($this->dtretorno) == null ){
           $this->erro_sql = " Campo Data arquivo nao Informado.";
           $this->erro_campo = "dtretorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtarquivo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtarquivo_dia"] !="") ){
       $sql  .= $virgula." dtarquivo = '$this->dtarquivo' ";
       $virgula = ",";
       if(trim($this->dtarquivo) == null ){
         $this->erro_sql = " Campo Data Arquivo nao Informado.";
         $this->erro_campo = "dtarquivo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtarquivo_dia"])){
         $sql  .= $virgula." dtarquivo = null ";
         $virgula = ",";
         if(trim($this->dtarquivo) == null ){
           $this->erro_sql = " Campo Data Arquivo nao Informado.";
           $this->erro_campo = "dtarquivo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_conta"])){
       $sql  .= $virgula." k00_conta = $this->k00_conta ";
       $virgula = ",";
       if(trim($this->k00_conta) == null ){
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "k00_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["autent"])){
       $sql  .= $virgula." autent = '$this->autent' ";
       $virgula = ",";
       if(trim($this->autent) == null ){
         $this->erro_sql = " Campo Autentica nao Informado.";
         $this->erro_campo = "autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->md5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["md5"])){
       $sql  .= $virgula." md5 = '$this->md5' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($codret!=null){
       $sql .= " codret = $this->codret";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codret));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1179,'$this->codret','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]) || $this->id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,213,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"]) || $this->k15_codbco != "")
           $resac = db_query("insert into db_acount values($acount,213,586,'".AddSlashes(pg_result($resaco,$conresaco,'k15_codbco'))."','$this->k15_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"]) || $this->k15_codage != "")
           $resac = db_query("insert into db_acount values($acount,213,587,'".AddSlashes(pg_result($resaco,$conresaco,'k15_codage'))."','$this->k15_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codret"]) || $this->codret != "")
           $resac = db_query("insert into db_acount values($acount,213,1179,'".AddSlashes(pg_result($resaco,$conresaco,'codret'))."','$this->codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["arqret"]) || $this->arqret != "")
           $resac = db_query("insert into db_acount values($acount,213,1180,'".AddSlashes(pg_result($resaco,$conresaco,'arqret'))."','$this->arqret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["textoret"]) || $this->textoret != "")
           $resac = db_query("insert into db_acount values($acount,213,1182,'".AddSlashes(pg_result($resaco,$conresaco,'textoret'))."','$this->textoret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtretorno"]) || $this->dtretorno != "")
           $resac = db_query("insert into db_acount values($acount,213,1183,'".AddSlashes(pg_result($resaco,$conresaco,'dtretorno'))."','$this->dtretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtarquivo"]) || $this->dtarquivo != "")
           $resac = db_query("insert into db_acount values($acount,213,1895,'".AddSlashes(pg_result($resaco,$conresaco,'dtarquivo'))."','$this->dtarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_conta"]) || $this->k00_conta != "")
           $resac = db_query("insert into db_acount values($acount,213,736,'".AddSlashes(pg_result($resaco,$conresaco,'k00_conta'))."','$this->k00_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["autent"]) || $this->autent != "")
           $resac = db_query("insert into db_acount values($acount,213,4858,'".AddSlashes(pg_result($resaco,$conresaco,'autent'))."','$this->autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["instit"]) || $this->instit != "")
           $resac = db_query("insert into db_acount values($acount,213,9996,'".AddSlashes(pg_result($resaco,$conresaco,'instit'))."','$this->instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["md5"]) || $this->md5 != "")
           $resac = db_query("insert into db_acount values($acount,213,19304,'".AddSlashes(pg_result($resaco,$conresaco,'md5'))."','$this->md5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos de Baixa banco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codret;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos de Baixa banco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($codret=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codret));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1179,'$codret','E')");
         $resac = db_query("insert into db_acount values($acount,213,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,586,'','".AddSlashes(pg_result($resaco,$iresaco,'k15_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,587,'','".AddSlashes(pg_result($resaco,$iresaco,'k15_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,1179,'','".AddSlashes(pg_result($resaco,$iresaco,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,1180,'','".AddSlashes(pg_result($resaco,$iresaco,'arqret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,1182,'','".AddSlashes(pg_result($resaco,$iresaco,'textoret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,1183,'','".AddSlashes(pg_result($resaco,$iresaco,'dtretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,1895,'','".AddSlashes(pg_result($resaco,$iresaco,'dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,736,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,4858,'','".AddSlashes(pg_result($resaco,$iresaco,'autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,9996,'','".AddSlashes(pg_result($resaco,$iresaco,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,213,19304,'','".AddSlashes(pg_result($resaco,$iresaco,'md5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disarq
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codret != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codret = $codret ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos de Baixa banco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codret;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos de Baixa banco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codret;
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
        $this->erro_sql   = "Record Vazio na Tabela:disarq";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $codret=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disarq ";
     $sql .= "      inner join db_config  on  db_config.codigo = disarq.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = disarq.id_usuario";
     $sql .= "      inner join saltes  on  saltes.k13_conta = disarq.k00_conta";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($codret!=null ){
         $sql2 .= " where disarq.codret = $codret ";
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
   function sql_query_file ( $codret=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disarq ";
     $sql2 = "";
     if($dbwhere==""){
       if($codret!=null ){
         $sql2 .= " where disarq.codret = $codret ";
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
   * SQL para busca de Arquivos com pagamento referentes a cobrança registrada.
   * @param  string $sCampos
   * @param  string $sWhere
   * @param  string $sOrderBy
   * @return string
   */
  function sql_query_integracaoTJ($sCampos = "*", $sWhere = "", $sOrderBy = "") {

    if ( !empty($sWhere) ) {
      $sWhere   = " and  " . $sWhere;
    }

    if ( !empty($sOrderBy) ) {
      $sOrderBy = " order by " . $sOrderBy;
    }
    $sCampos = str_replace("distinct", "", $sCampos);

    $sSql    = " select distinct                                                                           \n";
    $sSql   .= "        {$sCampos}                                                                         \n";
    $sSql   .= "   from processoforopartilhacusta pfpc                                                     \n";
    $sSql   .= "        inner join disbanco  on disbanco.k00_numpre                  = pfpc.v77_numnov     \n";
    $sSql   .= "                            and disbanco.k00_numpar                  = 0                   \n";
    $sSql   .= "                            and disbanco.classi                      is true               \n";
    $sSql   .= "        inner join disarq    on disarq.codret                        = disbanco.codret     \n";
    $sSql   .= "        left  join partilharemessawebservice prws on prws.v89_numnov = disbanco.k00_numpre \n";
    $sSql   .= "  where pfpc.v77_numnov <> 0                                                               \n";
    $sSql   .= "    and prws.v89_numnov is null                                                            \n";
    $sSql   .= $sWhere;
    $sSql   .= $sOrderBy;

    return $sSql;
  }


  function sql_query_baixa_banco ($codret=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from disarq ";
    $sql .= "      inner join disbanco  on disbanco.codret = disarq.codret";
    $sql .= "      inner join arreidret on arreidret.idret = disbanco.idret";
    $sql .= "      inner join arrepaga  on arrepaga.k00_numpre = arreidret.k00_numpre ";
    $sql .= "                          and arrepaga.k00_numpar = arreidret.k00_numpar ";
    $sql2 = "";
    if($dbwhere==""){
      if($codret!=null ){
        $sql2 .= " where disarq.codret = $codret ";
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
?>