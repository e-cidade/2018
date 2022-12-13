<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamjulgamentologitem
class cl_pcorcamjulgamentologitem { 
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
   var $pc93_sequencial = 0; 
   var $pc93_pcorcamjulgamentolog = 0; 
   var $pc93_pcorcamitem = 0; 
   var $pc93_pcorcamforne = 0; 
   var $pc93_valorunitario = 0; 
   var $pc93_pontuacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc93_sequencial = int4 = C�digo 
                 pc93_pcorcamjulgamentolog = int4 = C�digo do Julgamento Log 
                 pc93_pcorcamitem = int4 = Item do Or�amento 
                 pc93_pcorcamforne = int4 = Fornecedor do Or�amento 
                 pc93_valorunitario = float4 = Valor unit�rio 
                 pc93_pontuacao = int4 = Pontua��o 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamjulgamentologitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamjulgamentologitem"); 
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
       $this->pc93_sequencial = ($this->pc93_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_sequencial"]:$this->pc93_sequencial);
       $this->pc93_pcorcamjulgamentolog = ($this->pc93_pcorcamjulgamentolog == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamjulgamentolog"]:$this->pc93_pcorcamjulgamentolog);
       $this->pc93_pcorcamitem = ($this->pc93_pcorcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamitem"]:$this->pc93_pcorcamitem);
       $this->pc93_pcorcamforne = ($this->pc93_pcorcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamforne"]:$this->pc93_pcorcamforne);
       $this->pc93_valorunitario = ($this->pc93_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_valorunitario"]:$this->pc93_valorunitario);
       $this->pc93_pontuacao = ($this->pc93_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_pontuacao"]:$this->pc93_pontuacao);
     }else{
       $this->pc93_sequencial = ($this->pc93_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc93_sequencial"]:$this->pc93_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc93_sequencial){ 
      $this->atualizacampos();
     if($this->pc93_pcorcamjulgamentolog == null ){ 
       $this->erro_sql = " Campo C�digo do Julgamento Log nao Informado.";
       $this->erro_campo = "pc93_pcorcamjulgamentolog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc93_pcorcamitem == null ){ 
       $this->erro_sql = " Campo Item do Or�amento nao Informado.";
       $this->erro_campo = "pc93_pcorcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc93_pcorcamforne == null ){ 
       $this->erro_sql = " Campo Fornecedor do Or�amento nao Informado.";
       $this->erro_campo = "pc93_pcorcamforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc93_valorunitario == null ){ 
       $this->erro_sql = " Campo Valor unit�rio nao Informado.";
       $this->erro_campo = "pc93_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc93_pontuacao == null ){ 
       $this->erro_sql = " Campo Pontua��o nao Informado.";
       $this->erro_campo = "pc93_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc93_sequencial == "" || $pc93_sequencial == null ){
       $result = db_query("select nextval('pcorcamjulgamentologitem_pc93_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamjulgamentologitem_pc93_sequencial_seq do campo: pc93_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc93_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcorcamjulgamentologitem_pc93_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc93_sequencial)){
         $this->erro_sql = " Campo pc93_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc93_sequencial = $pc93_sequencial; 
       }
     }
     if(($this->pc93_sequencial == null) || ($this->pc93_sequencial == "") ){ 
       $this->erro_sql = " Campo pc93_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamjulgamentologitem(
                                       pc93_sequencial 
                                      ,pc93_pcorcamjulgamentolog 
                                      ,pc93_pcorcamitem 
                                      ,pc93_pcorcamforne 
                                      ,pc93_valorunitario 
                                      ,pc93_pontuacao 
                       )
                values (
                                $this->pc93_sequencial 
                               ,$this->pc93_pcorcamjulgamentolog 
                               ,$this->pc93_pcorcamitem 
                               ,$this->pc93_pcorcamforne 
                               ,$this->pc93_valorunitario 
                               ,$this->pc93_pontuacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log dos itens do julgamento ($this->pc93_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log dos itens do julgamento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log dos itens do julgamento ($this->pc93_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc93_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc93_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18834,'$this->pc93_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3340,18834,'','".AddSlashes(pg_result($resaco,0,'pc93_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3340,18835,'','".AddSlashes(pg_result($resaco,0,'pc93_pcorcamjulgamentolog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3340,18836,'','".AddSlashes(pg_result($resaco,0,'pc93_pcorcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3340,18837,'','".AddSlashes(pg_result($resaco,0,'pc93_pcorcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3340,18838,'','".AddSlashes(pg_result($resaco,0,'pc93_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3340,18839,'','".AddSlashes(pg_result($resaco,0,'pc93_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc93_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamjulgamentologitem set ";
     $virgula = "";
     if(trim($this->pc93_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_sequencial"])){ 
       $sql  .= $virgula." pc93_sequencial = $this->pc93_sequencial ";
       $virgula = ",";
       if(trim($this->pc93_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "pc93_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc93_pcorcamjulgamentolog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamjulgamentolog"])){ 
       $sql  .= $virgula." pc93_pcorcamjulgamentolog = $this->pc93_pcorcamjulgamentolog ";
       $virgula = ",";
       if(trim($this->pc93_pcorcamjulgamentolog) == null ){ 
         $this->erro_sql = " Campo C�digo do Julgamento Log nao Informado.";
         $this->erro_campo = "pc93_pcorcamjulgamentolog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc93_pcorcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamitem"])){ 
       $sql  .= $virgula." pc93_pcorcamitem = $this->pc93_pcorcamitem ";
       $virgula = ",";
       if(trim($this->pc93_pcorcamitem) == null ){ 
         $this->erro_sql = " Campo Item do Or�amento nao Informado.";
         $this->erro_campo = "pc93_pcorcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc93_pcorcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamforne"])){ 
       $sql  .= $virgula." pc93_pcorcamforne = $this->pc93_pcorcamforne ";
       $virgula = ",";
       if(trim($this->pc93_pcorcamforne) == null ){ 
         $this->erro_sql = " Campo Fornecedor do Or�amento nao Informado.";
         $this->erro_campo = "pc93_pcorcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc93_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_valorunitario"])){ 
       $sql  .= $virgula." pc93_valorunitario = $this->pc93_valorunitario ";
       $virgula = ",";
       if(trim($this->pc93_valorunitario) == null ){ 
         $this->erro_sql = " Campo Valor unit�rio nao Informado.";
         $this->erro_campo = "pc93_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc93_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc93_pontuacao"])){ 
       $sql  .= $virgula." pc93_pontuacao = $this->pc93_pontuacao ";
       $virgula = ",";
       if(trim($this->pc93_pontuacao) == null ){ 
         $this->erro_sql = " Campo Pontua��o nao Informado.";
         $this->erro_campo = "pc93_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc93_sequencial!=null){
       $sql .= " pc93_sequencial = $this->pc93_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc93_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18834,'$this->pc93_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_sequencial"]) || $this->pc93_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3340,18834,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_sequencial'))."','$this->pc93_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamjulgamentolog"]) || $this->pc93_pcorcamjulgamentolog != "")
           $resac = db_query("insert into db_acount values($acount,3340,18835,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_pcorcamjulgamentolog'))."','$this->pc93_pcorcamjulgamentolog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamitem"]) || $this->pc93_pcorcamitem != "")
           $resac = db_query("insert into db_acount values($acount,3340,18836,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_pcorcamitem'))."','$this->pc93_pcorcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_pcorcamforne"]) || $this->pc93_pcorcamforne != "")
           $resac = db_query("insert into db_acount values($acount,3340,18837,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_pcorcamforne'))."','$this->pc93_pcorcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_valorunitario"]) || $this->pc93_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,3340,18838,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_valorunitario'))."','$this->pc93_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc93_pontuacao"]) || $this->pc93_pontuacao != "")
           $resac = db_query("insert into db_acount values($acount,3340,18839,'".AddSlashes(pg_result($resaco,$conresaco,'pc93_pontuacao'))."','$this->pc93_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log dos itens do julgamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc93_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log dos itens do julgamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc93_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc93_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc93_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc93_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18834,'$pc93_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3340,18834,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3340,18835,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_pcorcamjulgamentolog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3340,18836,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_pcorcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3340,18837,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_pcorcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3340,18838,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3340,18839,'','".AddSlashes(pg_result($resaco,$iresaco,'pc93_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamjulgamentologitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc93_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc93_sequencial = $pc93_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log dos itens do julgamento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc93_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log dos itens do julgamento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc93_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc93_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamjulgamentologitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc93_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamjulgamentologitem ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamjulgamentologitem.pc93_pcorcamforne";
     $sql .= "      inner join pcorcamitem   on  pcorcamitem.pc22_orcamitem = pcorcamjulgamentologitem.pc93_pcorcamitem";
     $sql .= "      inner join pcorcamjulgamentolog  on  pcorcamjulgamentolog.pc92_sequencial = pcorcamjulgamentologitem.pc93_pcorcamjulgamentolog";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam      on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcorcamjulgamentolog.pc92_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc93_sequencial!=null ){
         $sql2 .= " where pcorcamjulgamentologitem.pc93_sequencial = $pc93_sequencial "; 
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
   function sql_query_file ( $pc93_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamjulgamentologitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc93_sequencial!=null ){
         $sql2 .= " where pcorcamjulgamentologitem.pc93_sequencial = $pc93_sequencial "; 
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