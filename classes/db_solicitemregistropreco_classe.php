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
//CLASSE DA ENTIDADE solicitemregistropreco
class cl_solicitemregistropreco { 
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
   var $pc57_sequencial = 0; 
   var $pc57_solicitem = 0; 
   var $pc57_quantmin = 0; 
   var $pc57_quantmax = 0; 
   var $pc57_itemorigem = 0; 
   var $pc57_ativo = 'f'; 
   var $pc57_quantidadeexecedente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc57_sequencial = int4 = Código 
                 pc57_solicitem = int4 = Codigo Item 
                 pc57_quantmin = float8 = Quantidade Minima 
                 pc57_quantmax = float8 = Quantidade Máxima 
                 pc57_itemorigem = int4 = Item de  Origem 
                 pc57_ativo = bool = Item Ativo 
                 pc57_quantidadeexecedente = float8 = Quantidade Execente 
                 ";
   //funcao construtor da classe 
   function cl_solicitemregistropreco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitemregistropreco"); 
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
       $this->pc57_sequencial = ($this->pc57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_sequencial"]:$this->pc57_sequencial);
       $this->pc57_solicitem = ($this->pc57_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_solicitem"]:$this->pc57_solicitem);
       $this->pc57_quantmin = ($this->pc57_quantmin == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_quantmin"]:$this->pc57_quantmin);
       $this->pc57_quantmax = ($this->pc57_quantmax == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_quantmax"]:$this->pc57_quantmax);
       $this->pc57_itemorigem = ($this->pc57_itemorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_itemorigem"]:$this->pc57_itemorigem);
       $this->pc57_ativo = ($this->pc57_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc57_ativo"]:$this->pc57_ativo);
       $this->pc57_quantidadeexecedente = ($this->pc57_quantidadeexecedente == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_quantidadeexecedente"]:$this->pc57_quantidadeexecedente);
     }else{
       $this->pc57_sequencial = ($this->pc57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc57_sequencial"]:$this->pc57_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc57_sequencial){ 
      $this->atualizacampos();
     if($this->pc57_solicitem == null ){ 
       $this->erro_sql = " Campo Codigo Item nao Informado.";
       $this->erro_campo = "pc57_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc57_quantmin == null ){ 
       $this->pc57_quantmin = "0";
     }
     if($this->pc57_quantmax == null ){ 
       $this->erro_sql = " Campo Quantidade Máxima nao Informado.";
       $this->erro_campo = "pc57_quantmax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc57_itemorigem == null ){ 
       $this->pc57_itemorigem = "null";
     }
     if($this->pc57_ativo == null ){ 
       $this->erro_sql = " Campo Item Ativo nao Informado.";
       $this->erro_campo = "pc57_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc57_quantidadeexecedente == null ){ 
       $this->pc57_quantidadeexecedente = "0";
     }
     if($pc57_sequencial == "" || $pc57_sequencial == null ){
       $result = db_query("select nextval('solicitemregistropreco_pc57_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitemregistropreco_pc57_sequencial_seq do campo: pc57_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc57_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solicitemregistropreco_pc57_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc57_sequencial)){
         $this->erro_sql = " Campo pc57_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc57_sequencial = $pc57_sequencial; 
       }
     }
     if(($this->pc57_sequencial == null) || ($this->pc57_sequencial == "") ){ 
       $this->erro_sql = " Campo pc57_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitemregistropreco(
                                       pc57_sequencial 
                                      ,pc57_solicitem 
                                      ,pc57_quantmin 
                                      ,pc57_quantmax 
                                      ,pc57_itemorigem 
                                      ,pc57_ativo 
                                      ,pc57_quantidadeexecedente 
                       )
                values (
                                $this->pc57_sequencial 
                               ,$this->pc57_solicitem 
                               ,$this->pc57_quantmin 
                               ,$this->pc57_quantmax 
                               ,$this->pc57_itemorigem 
                               ,'$this->pc57_ativo' 
                               ,$this->pc57_quantidadeexecedente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de preço do item ($this->pc57_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de preço do item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de preço do item ($this->pc57_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc57_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc57_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15223,'$this->pc57_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2682,15223,'','".AddSlashes(pg_result($resaco,0,'pc57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,15224,'','".AddSlashes(pg_result($resaco,0,'pc57_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,15225,'','".AddSlashes(pg_result($resaco,0,'pc57_quantmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,15226,'','".AddSlashes(pg_result($resaco,0,'pc57_quantmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,15227,'','".AddSlashes(pg_result($resaco,0,'pc57_itemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,15228,'','".AddSlashes(pg_result($resaco,0,'pc57_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2682,18369,'','".AddSlashes(pg_result($resaco,0,'pc57_quantidadeexecedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc57_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update solicitemregistropreco set ";
     $virgula = "";
     if(trim($this->pc57_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_sequencial"])){ 
       $sql  .= $virgula." pc57_sequencial = $this->pc57_sequencial ";
       $virgula = ",";
       if(trim($this->pc57_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc57_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc57_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_solicitem"])){ 
       $sql  .= $virgula." pc57_solicitem = $this->pc57_solicitem ";
       $virgula = ",";
       if(trim($this->pc57_solicitem) == null ){ 
         $this->erro_sql = " Campo Codigo Item nao Informado.";
         $this->erro_campo = "pc57_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc57_quantmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantmin"])){ 
        if(trim($this->pc57_quantmin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantmin"])){ 
           $this->pc57_quantmin = "0" ; 
        } 
       $sql  .= $virgula." pc57_quantmin = $this->pc57_quantmin ";
       $virgula = ",";
     }
     if(trim($this->pc57_quantmax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantmax"])){ 
       $sql  .= $virgula." pc57_quantmax = $this->pc57_quantmax ";
       $virgula = ",";
       if(trim($this->pc57_quantmax) == null ){ 
         $this->erro_sql = " Campo Quantidade Máxima nao Informado.";
         $this->erro_campo = "pc57_quantmax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc57_itemorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_itemorigem"])){ 
        if(trim($this->pc57_itemorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc57_itemorigem"])){ 
           $this->pc57_itemorigem = "0" ; 
        } 
       $sql  .= $virgula." pc57_itemorigem = $this->pc57_itemorigem ";
       $virgula = ",";
     }
     if(trim($this->pc57_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_ativo"])){ 
       $sql  .= $virgula." pc57_ativo = '$this->pc57_ativo' ";
       $virgula = ",";
       if(trim($this->pc57_ativo) == null ){ 
         $this->erro_sql = " Campo Item Ativo nao Informado.";
         $this->erro_campo = "pc57_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc57_quantidadeexecedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantidadeexecedente"])){ 
        if(trim($this->pc57_quantidadeexecedente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantidadeexecedente"])){ 
           $this->pc57_quantidadeexecedente = "0" ; 
        } 
       $sql  .= $virgula." pc57_quantidadeexecedente = $this->pc57_quantidadeexecedente ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc57_sequencial!=null){
       $sql .= " pc57_sequencial = $this->pc57_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc57_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15223,'$this->pc57_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_sequencial"]) || $this->pc57_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2682,15223,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_sequencial'))."','$this->pc57_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_solicitem"]) || $this->pc57_solicitem != "")
           $resac = db_query("insert into db_acount values($acount,2682,15224,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_solicitem'))."','$this->pc57_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantmin"]) || $this->pc57_quantmin != "")
           $resac = db_query("insert into db_acount values($acount,2682,15225,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_quantmin'))."','$this->pc57_quantmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantmax"]) || $this->pc57_quantmax != "")
           $resac = db_query("insert into db_acount values($acount,2682,15226,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_quantmax'))."','$this->pc57_quantmax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_itemorigem"]) || $this->pc57_itemorigem != "")
           $resac = db_query("insert into db_acount values($acount,2682,15227,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_itemorigem'))."','$this->pc57_itemorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_ativo"]) || $this->pc57_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2682,15228,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_ativo'))."','$this->pc57_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc57_quantidadeexecedente"]) || $this->pc57_quantidadeexecedente != "")
           $resac = db_query("insert into db_acount values($acount,2682,18369,'".AddSlashes(pg_result($resaco,$conresaco,'pc57_quantidadeexecedente'))."','$this->pc57_quantidadeexecedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de preço do item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de preço do item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc57_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc57_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15223,'$pc57_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2682,15223,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,15224,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,15225,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_quantmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,15226,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_quantmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,15227,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_itemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,15228,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2682,18369,'','".AddSlashes(pg_result($resaco,$iresaco,'pc57_quantidadeexecedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitemregistropreco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc57_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc57_sequencial = $pc57_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de preço do item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de preço do item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc57_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitemregistropreco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc57_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitemregistropreco ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = solicitemregistropreco.pc57_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc57_sequencial!=null ){
         $sql2 .= " where solicitemregistropreco.pc57_sequencial = $pc57_sequencial "; 
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
   function sql_query_file ( $pc57_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitemregistropreco ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc57_sequencial!=null ){
         $sql2 .= " where solicitemregistropreco.pc57_sequencial = $pc57_sequencial "; 
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
   function sql_query_orcamento ( $pc57_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitemregistropreco ";
     $sql .= "      inner join solicitem        on solicitem.pc11_codigo = solicitemregistropreco.pc57_solicitem";
     $sql .= "      inner join solicita         on solicita.pc10_numero  = solicitem.pc11_numero";
     $sql .= "      left  join pcprocitem       on pc11_codigo           = pc81_solicitem";
     $sql .= "      left  join liclicitem       on pc81_codprocitem      = l21_codpcprocitem";
     $sql .= "      left  join pcorcamitemlic   on l21_codigo            = pc26_liclicitem";
     $sql .= "      left  join pcorcamitem      on pc26_orcamitem        = pc22_orcamitem   ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc57_sequencial!=null ){
         $sql2 .= " where solicitemregistropreco.pc57_sequencial = $pc57_sequencial "; 
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