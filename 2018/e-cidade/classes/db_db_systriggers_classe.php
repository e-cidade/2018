<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_systriggers
class cl_db_systriggers { 
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
   var $codtrigger = 0; 
   var $nometrigger = null; 
   var $quandotrigger = null; 
   var $erro = null; 
   var $codfuncao = 0; 
   var $codarq = 0; 
   var $eventotrigger = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codtrigger = int4 = Código 
                 nometrigger = varchar(50) = Nome 
                 quandotrigger = varchar(6) = Quando 
                 erro = char(6) = Erro 
                 codfuncao = int4 = Código Função 
                 codarq = int4 = Codigo Arquivo 
                 eventotrigger = varchar(40) = Evento 
                 ";
   //funcao construtor da classe 
   function cl_db_systriggers() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_systriggers"); 
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
       $this->codtrigger = ($this->codtrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["codtrigger"]:$this->codtrigger);
       $this->nometrigger = ($this->nometrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["nometrigger"]:$this->nometrigger);
       $this->quandotrigger = ($this->quandotrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["quandotrigger"]:$this->quandotrigger);
       $this->erro = ($this->erro == ""?@$GLOBALS["HTTP_POST_VARS"]["erro"]:$this->erro);
       $this->codfuncao = ($this->codfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["codfuncao"]:$this->codfuncao);
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->eventotrigger = ($this->eventotrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["eventotrigger"]:$this->eventotrigger);
     }else{
       $this->codtrigger = ($this->codtrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["codtrigger"]:$this->codtrigger);
     }
   }
   // funcao para inclusao
   function incluir ($codtrigger){ 
      $this->atualizacampos();
     if($this->nometrigger == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "nometrigger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->quandotrigger == null ){ 
       $this->erro_sql = " Campo Quando nao Informado.";
       $this->erro_campo = "quandotrigger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->erro == null ){ 
       $this->erro_sql = " Campo Erro nao Informado.";
       $this->erro_campo = "erro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codfuncao == null ){ 
       $this->erro_sql = " Campo Código Função nao Informado.";
       $this->erro_campo = "codfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codarq == null ){ 
       $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
       $this->erro_campo = "codarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eventotrigger == null ){ 
       $this->erro_sql = " Campo Evento nao Informado.";
       $this->erro_campo = "eventotrigger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codtrigger == "" || $codtrigger == null ){
       $result = db_query("select nextval('db_systriggers_codtrigger_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_systriggers_codtrigger_seq do campo: codtrigger"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codtrigger = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_systriggers_codtrigger_seq");
       if(($result != false) && (pg_result($result,0,0) < $codtrigger)){
         $this->erro_sql = " Campo codtrigger maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codtrigger = $codtrigger; 
       }
     }
     if(($this->codtrigger == null) || ($this->codtrigger == "") ){ 
       $this->erro_sql = " Campo codtrigger nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_systriggers(
                                       codtrigger 
                                      ,nometrigger 
                                      ,quandotrigger 
                                      ,erro 
                                      ,codfuncao 
                                      ,codarq 
                                      ,eventotrigger 
                       )
                values (
                                $this->codtrigger 
                               ,'$this->nometrigger' 
                               ,'$this->quandotrigger' 
                               ,'$this->erro' 
                               ,$this->codfuncao 
                               ,$this->codarq 
                               ,'$this->eventotrigger' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Triggers (Gatilhos) ($this->codtrigger) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Triggers (Gatilhos) já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Triggers (Gatilhos) ($this->codtrigger) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codtrigger;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codtrigger));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,779,'$this->codtrigger','I')");
       $resac = db_query("insert into db_acount values($acount,151,779,'','".AddSlashes(pg_result($resaco,0,'codtrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,780,'','".AddSlashes(pg_result($resaco,0,'nometrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,781,'','".AddSlashes(pg_result($resaco,0,'quandotrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,10738,'','".AddSlashes(pg_result($resaco,0,'erro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,774,'','".AddSlashes(pg_result($resaco,0,'codfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,759,'','".AddSlashes(pg_result($resaco,0,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,151,782,'','".AddSlashes(pg_result($resaco,0,'eventotrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codtrigger=null) { 
      $this->atualizacampos();
     $sql = " update db_systriggers set ";
     $virgula = "";
     if(trim($this->codtrigger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codtrigger"])){ 
       $sql  .= $virgula." codtrigger = $this->codtrigger ";
       $virgula = ",";
       if(trim($this->codtrigger) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codtrigger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nometrigger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nometrigger"])){ 
       $sql  .= $virgula." nometrigger = '$this->nometrigger' ";
       $virgula = ",";
       if(trim($this->nometrigger) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "nometrigger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->quandotrigger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["quandotrigger"])){ 
       $sql  .= $virgula." quandotrigger = '$this->quandotrigger' ";
       $virgula = ",";
       if(trim($this->quandotrigger) == null ){ 
         $this->erro_sql = " Campo Quando nao Informado.";
         $this->erro_campo = "quandotrigger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->erro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["erro"])){ 
       $sql  .= $virgula." erro = '$this->erro' ";
       $virgula = ",";
       if(trim($this->erro) == null ){ 
         $this->erro_sql = " Campo Erro nao Informado.";
         $this->erro_campo = "erro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codfuncao"])){ 
       $sql  .= $virgula." codfuncao = $this->codfuncao ";
       $virgula = ",";
       if(trim($this->codfuncao) == null ){ 
         $this->erro_sql = " Campo Código Função nao Informado.";
         $this->erro_campo = "codfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codarq"])){ 
       $sql  .= $virgula." codarq = $this->codarq ";
       $virgula = ",";
       if(trim($this->codarq) == null ){ 
         $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
         $this->erro_campo = "codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eventotrigger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eventotrigger"])){ 
       $sql  .= $virgula." eventotrigger = '$this->eventotrigger' ";
       $virgula = ",";
       if(trim($this->eventotrigger) == null ){ 
         $this->erro_sql = " Campo Evento nao Informado.";
         $this->erro_campo = "eventotrigger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codtrigger!=null){
       $sql .= " codtrigger = $this->codtrigger";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codtrigger));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,779,'$this->codtrigger','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codtrigger"]))
           $resac = db_query("insert into db_acount values($acount,151,779,'".AddSlashes(pg_result($resaco,$conresaco,'codtrigger'))."','$this->codtrigger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nometrigger"]))
           $resac = db_query("insert into db_acount values($acount,151,780,'".AddSlashes(pg_result($resaco,$conresaco,'nometrigger'))."','$this->nometrigger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["quandotrigger"]))
           $resac = db_query("insert into db_acount values($acount,151,781,'".AddSlashes(pg_result($resaco,$conresaco,'quandotrigger'))."','$this->quandotrigger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["erro"]))
           $resac = db_query("insert into db_acount values($acount,151,10738,'".AddSlashes(pg_result($resaco,$conresaco,'erro'))."','$this->erro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codfuncao"]))
           $resac = db_query("insert into db_acount values($acount,151,774,'".AddSlashes(pg_result($resaco,$conresaco,'codfuncao'))."','$this->codfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codarq"]))
           $resac = db_query("insert into db_acount values($acount,151,759,'".AddSlashes(pg_result($resaco,$conresaco,'codarq'))."','$this->codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["eventotrigger"]))
           $resac = db_query("insert into db_acount values($acount,151,782,'".AddSlashes(pg_result($resaco,$conresaco,'eventotrigger'))."','$this->eventotrigger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Triggers (Gatilhos) nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codtrigger;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Triggers (Gatilhos) nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codtrigger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codtrigger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codtrigger=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codtrigger));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,779,'$codtrigger','E')");
         $resac = db_query("insert into db_acount values($acount,151,779,'','".AddSlashes(pg_result($resaco,$iresaco,'codtrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,780,'','".AddSlashes(pg_result($resaco,$iresaco,'nometrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,781,'','".AddSlashes(pg_result($resaco,$iresaco,'quandotrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,10738,'','".AddSlashes(pg_result($resaco,$iresaco,'erro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,774,'','".AddSlashes(pg_result($resaco,$iresaco,'codfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,759,'','".AddSlashes(pg_result($resaco,$iresaco,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,151,782,'','".AddSlashes(pg_result($resaco,$iresaco,'eventotrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_systriggers
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codtrigger != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codtrigger = $codtrigger ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Triggers (Gatilhos) nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codtrigger;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Triggers (Gatilhos) nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codtrigger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codtrigger;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_systriggers";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>