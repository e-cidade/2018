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

//MODULO: orcamento
//CLASSE DA ENTIDADE pactovalorsaldo
class cl_pactovalorsaldo { 
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
   var $o103_sequencial = 0; 
   var $o103_pactovalor = 0; 
   var $o103_pactovalorsaldotipo = 0; 
   var $o103_anousu = 0; 
   var $o103_mesusu = 0; 
   var $o103_valor = 0; 
   var $o103_contrapartida = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o103_sequencial = int4 = Sequencial 
                 o103_pactovalor = int4 = Item 
                 o103_pactovalorsaldotipo = int4 = Tipo Item 
                 o103_anousu = int4 = Ano 
                 o103_mesusu = int4 = Mes 
                 o103_valor = float4 = Valor 
                 o103_contrapartida = bool = Contrapartida 
                 ";
   //funcao construtor da classe 
   function cl_pactovalorsaldo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pactovalorsaldo"); 
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
       $this->o103_sequencial = ($this->o103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_sequencial"]:$this->o103_sequencial);
       $this->o103_pactovalor = ($this->o103_pactovalor == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_pactovalor"]:$this->o103_pactovalor);
       $this->o103_pactovalorsaldotipo = ($this->o103_pactovalorsaldotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_pactovalorsaldotipo"]:$this->o103_pactovalorsaldotipo);
       $this->o103_anousu = ($this->o103_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_anousu"]:$this->o103_anousu);
       $this->o103_mesusu = ($this->o103_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_mesusu"]:$this->o103_mesusu);
       $this->o103_valor = ($this->o103_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_valor"]:$this->o103_valor);
       $this->o103_contrapartida = ($this->o103_contrapartida == "f"?@$GLOBALS["HTTP_POST_VARS"]["o103_contrapartida"]:$this->o103_contrapartida);
     }else{
       $this->o103_sequencial = ($this->o103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o103_sequencial"]:$this->o103_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o103_sequencial){ 
      $this->atualizacampos();
     if($this->o103_pactovalor == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "o103_pactovalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o103_pactovalorsaldotipo == null ){ 
       $this->erro_sql = " Campo Tipo Item nao Informado.";
       $this->erro_campo = "o103_pactovalorsaldotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o103_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o103_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o103_mesusu == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "o103_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o103_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o103_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o103_contrapartida == null ){ 
       $this->erro_sql = " Campo Contrapartida nao Informado.";
       $this->erro_campo = "o103_contrapartida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o103_sequencial == "" || $o103_sequencial == null ){
       $result = db_query("select nextval('pactovalorsaldo_o103_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pactovalorsaldo_o103_sequencial_seq do campo: o103_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o103_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pactovalorsaldo_o103_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o103_sequencial)){
         $this->erro_sql = " Campo o103_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o103_sequencial = $o103_sequencial; 
       }
     }
     if(($this->o103_sequencial == null) || ($this->o103_sequencial == "") ){ 
       $this->erro_sql = " Campo o103_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pactovalorsaldo(
                                       o103_sequencial 
                                      ,o103_pactovalor 
                                      ,o103_pactovalorsaldotipo 
                                      ,o103_anousu 
                                      ,o103_mesusu 
                                      ,o103_valor 
                                      ,o103_contrapartida 
                       )
                values (
                                $this->o103_sequencial 
                               ,$this->o103_pactovalor 
                               ,$this->o103_pactovalorsaldotipo 
                               ,$this->o103_anousu 
                               ,$this->o103_mesusu 
                               ,$this->o103_valor 
                               ,'$this->o103_contrapartida' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pactovalorsaldo ($this->o103_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pactovalorsaldo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pactovalorsaldo ($this->o103_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o103_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o103_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13928,'$this->o103_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2447,13928,'','".AddSlashes(pg_result($resaco,0,'o103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13929,'','".AddSlashes(pg_result($resaco,0,'o103_pactovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13932,'','".AddSlashes(pg_result($resaco,0,'o103_pactovalorsaldotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13933,'','".AddSlashes(pg_result($resaco,0,'o103_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13934,'','".AddSlashes(pg_result($resaco,0,'o103_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13935,'','".AddSlashes(pg_result($resaco,0,'o103_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2447,13984,'','".AddSlashes(pg_result($resaco,0,'o103_contrapartida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o103_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pactovalorsaldo set ";
     $virgula = "";
     if(trim($this->o103_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_sequencial"])){ 
       $sql  .= $virgula." o103_sequencial = $this->o103_sequencial ";
       $virgula = ",";
       if(trim($this->o103_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o103_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_pactovalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_pactovalor"])){ 
       $sql  .= $virgula." o103_pactovalor = $this->o103_pactovalor ";
       $virgula = ",";
       if(trim($this->o103_pactovalor) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "o103_pactovalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_pactovalorsaldotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_pactovalorsaldotipo"])){ 
       $sql  .= $virgula." o103_pactovalorsaldotipo = $this->o103_pactovalorsaldotipo ";
       $virgula = ",";
       if(trim($this->o103_pactovalorsaldotipo) == null ){ 
         $this->erro_sql = " Campo Tipo Item nao Informado.";
         $this->erro_campo = "o103_pactovalorsaldotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_anousu"])){ 
       $sql  .= $virgula." o103_anousu = $this->o103_anousu ";
       $virgula = ",";
       if(trim($this->o103_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o103_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_mesusu"])){ 
       $sql  .= $virgula." o103_mesusu = $this->o103_mesusu ";
       $virgula = ",";
       if(trim($this->o103_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "o103_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_valor"])){ 
       $sql  .= $virgula." o103_valor = $this->o103_valor ";
       $virgula = ",";
       if(trim($this->o103_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o103_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o103_contrapartida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o103_contrapartida"])){ 
       $sql  .= $virgula." o103_contrapartida = '$this->o103_contrapartida' ";
       $virgula = ",";
       if(trim($this->o103_contrapartida) == null ){ 
         $this->erro_sql = " Campo Contrapartida nao Informado.";
         $this->erro_campo = "o103_contrapartida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o103_sequencial!=null){
       $sql .= " o103_sequencial = $this->o103_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o103_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13928,'$this->o103_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2447,13928,'".AddSlashes(pg_result($resaco,$conresaco,'o103_sequencial'))."','$this->o103_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_pactovalor"]))
           $resac = db_query("insert into db_acount values($acount,2447,13929,'".AddSlashes(pg_result($resaco,$conresaco,'o103_pactovalor'))."','$this->o103_pactovalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_pactovalorsaldotipo"]))
           $resac = db_query("insert into db_acount values($acount,2447,13932,'".AddSlashes(pg_result($resaco,$conresaco,'o103_pactovalorsaldotipo'))."','$this->o103_pactovalorsaldotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2447,13933,'".AddSlashes(pg_result($resaco,$conresaco,'o103_anousu'))."','$this->o103_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,2447,13934,'".AddSlashes(pg_result($resaco,$conresaco,'o103_mesusu'))."','$this->o103_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_valor"]))
           $resac = db_query("insert into db_acount values($acount,2447,13935,'".AddSlashes(pg_result($resaco,$conresaco,'o103_valor'))."','$this->o103_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o103_contrapartida"]))
           $resac = db_query("insert into db_acount values($acount,2447,13984,'".AddSlashes(pg_result($resaco,$conresaco,'o103_contrapartida'))."','$this->o103_contrapartida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pactovalorsaldo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pactovalorsaldo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o103_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o103_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13928,'$o103_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2447,13928,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13929,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_pactovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13932,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_pactovalorsaldotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13933,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13934,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13935,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2447,13984,'','".AddSlashes(pg_result($resaco,$iresaco,'o103_contrapartida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pactovalorsaldo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o103_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o103_sequencial = $o103_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pactovalorsaldo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pactovalorsaldo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o103_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pactovalorsaldo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pactovalorsaldo ";
     $sql .= "      inner join pactovalor  on  pactovalor.o87_sequencial = pactovalorsaldo.o103_pactovalor";
     $sql .= "      inner join pactovalorsaldotipo  on  pactovalorsaldotipo.o104_sequencial = pactovalorsaldo.o103_pactovalorsaldotipo";
     $sql .= "      left  join orcprojativ  on  orcprojativ.o55_anousu = pactovalor.o87_orcprojativanoprojeto and  orcprojativ.o55_projativ = pactovalor.o87_orcprojativativprojeto";
     $sql .= "      left  join pactoacoes  on  pactoacoes.o79_sequencial = pactovalor.o87_pactoacoes";
     $sql .= "      inner join pactoplano  on  pactoplano.o74_sequencial = pactovalor.o87_pactoplano";
     $sql .= "      left  join categoriapacto  on  categoriapacto.o31_sequencial = pactovalor.o87_categoriapacto";
     $sql .= "      left  join pactoitem  on  pactoitem.o109_sequencial = pactovalor.o87_pactoitem";
     $sql .= "      left  join pactoatividade  on  pactoatividade.o104_sequencial = pactovalor.o87_pactoatividade";
     $sql .= "      left  join pactoprograma  on  pactoprograma.o107_sequencial = pactovalor.o87_pactoprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($o103_sequencial!=null ){
         $sql2 .= " where pactovalorsaldo.o103_sequencial = $o103_sequencial "; 
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
   function sql_query_file ( $o103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pactovalorsaldo ";
     $sql2 = "";
     if($dbwhere==""){
       if($o103_sequencial!=null ){
         $sql2 .= " where pactovalorsaldo.o103_sequencial = $o103_sequencial "; 
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