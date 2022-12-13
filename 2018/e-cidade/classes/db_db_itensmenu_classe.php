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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_itensmenu
class cl_db_itensmenu { 
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
   var $id_item = 0; 
   var $descricao = null; 
   var $help = null; 
   var $funcao = null; 
   var $itemativo = null; 
   var $manutencao = null; 
   var $desctec = null; 
   var $libcliente = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_item = int4 = Código do ítem 
                 descricao = text = Descrição 
                 help = text = Ajuda 
                 funcao = varchar(100) = função 
                 itemativo = char(1) = Ítem ativo 
                 manutencao = char(1) = Manutenção 
                 desctec = text = Descrição Técnica 
                 libcliente = bool = Se item de menu está liberado para cliente 
                 ";
   //funcao construtor da classe 
   function cl_db_itensmenu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_itensmenu"); 
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
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->descricao = ($this->descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["descricao"]:$this->descricao);
       $this->help = ($this->help == ""?@$GLOBALS["HTTP_POST_VARS"]["help"]:$this->help);
       $this->funcao = ($this->funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["funcao"]:$this->funcao);
       $this->itemativo = ($this->itemativo == ""?@$GLOBALS["HTTP_POST_VARS"]["itemativo"]:$this->itemativo);
       $this->manutencao = ($this->manutencao == ""?@$GLOBALS["HTTP_POST_VARS"]["manutencao"]:$this->manutencao);
       $this->desctec = ($this->desctec == ""?@$GLOBALS["HTTP_POST_VARS"]["desctec"]:$this->desctec);
       $this->libcliente = ($this->libcliente == "f"?@$GLOBALS["HTTP_POST_VARS"]["libcliente"]:$this->libcliente);
     }else{
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
     }
   }
   // funcao para inclusao
   function incluir ($id_item){ 
      $this->atualizacampos();
     if($this->descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->help == null ){ 
       $this->erro_sql = " Campo Ajuda não informado.";
       $this->erro_campo = "help";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->itemativo == null ){ 
       $this->erro_sql = " Campo Ítem ativo não informado.";
       $this->erro_campo = "itemativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->manutencao == null ){ 
       $this->erro_sql = " Campo Manutenção não informado.";
       $this->erro_campo = "manutencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->desctec == null ){ 
       $this->erro_sql = " Campo Descrição Técnica não informado.";
       $this->erro_campo = "desctec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->libcliente == null ){ 
       $this->erro_sql = " Campo Se item de menu está liberado para cliente não informado.";
       $this->erro_campo = "libcliente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($id_item == "" || $id_item == null ){
       $result = db_query("select nextval('db_itensmenu_id_item_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_itensmenu_id_item_seq do campo: id_item"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->id_item = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_itensmenu_id_item_seq");
       if(($result != false) && (pg_result($result,0,0) < $id_item)){
         $this->erro_sql = " Campo id_item maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->id_item = $id_item; 
       }
     }
     if(($this->id_item == null) || ($this->id_item == "") ){ 
       $this->erro_sql = " Campo id_item nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_itensmenu(
                                       id_item 
                                      ,descricao 
                                      ,help 
                                      ,funcao 
                                      ,itemativo 
                                      ,manutencao 
                                      ,desctec 
                                      ,libcliente 
                       )
                values (
                                $this->id_item 
                               ,'$this->descricao' 
                               ,'$this->help' 
                               ,'$this->funcao' 
                               ,'$this->itemativo' 
                               ,'$this->manutencao' 
                               ,'$this->desctec' 
                               ,'$this->libcliente' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens do menu ($this->id_item) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens do menu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens do menu ($this->id_item) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_item;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->id_item  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','I')");
         $resac = db_query("insert into db_acount values($acount,156,821,'','".AddSlashes(pg_result($resaco,0,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,750,'','".AddSlashes(pg_result($resaco,0,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,823,'','".AddSlashes(pg_result($resaco,0,'help'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,824,'','".AddSlashes(pg_result($resaco,0,'funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,825,'','".AddSlashes(pg_result($resaco,0,'itemativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,826,'','".AddSlashes(pg_result($resaco,0,'manutencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,827,'','".AddSlashes(pg_result($resaco,0,'desctec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,156,8923,'','".AddSlashes(pg_result($resaco,0,'libcliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_item=null) { 
      $this->atualizacampos();
     $sql = " update db_itensmenu set ";
     $virgula = "";
     if(trim($this->id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item"])){ 
       $sql  .= $virgula." id_item = $this->id_item ";
       $virgula = ",";
       if(trim($this->id_item) == null ){ 
         $this->erro_sql = " Campo Código do ítem não informado.";
         $this->erro_campo = "id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descricao"])){ 
       $sql  .= $virgula." descricao = '$this->descricao' ";
       $virgula = ",";
       if(trim($this->descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->help)!="" || isset($GLOBALS["HTTP_POST_VARS"]["help"])){ 
       $sql  .= $virgula." help = '$this->help' ";
       $virgula = ",";
       if(trim($this->help) == null ){ 
         $this->erro_sql = " Campo Ajuda não informado.";
         $this->erro_campo = "help";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["funcao"])){ 
       $sql  .= $virgula." funcao = '$this->funcao' ";
       $virgula = ",";
     }
     if(trim($this->itemativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["itemativo"])){ 
       $sql  .= $virgula." itemativo = '$this->itemativo' ";
       $virgula = ",";
       if(trim($this->itemativo) == null ){ 
         $this->erro_sql = " Campo Ítem ativo não informado.";
         $this->erro_campo = "itemativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->manutencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["manutencao"])){ 
       $sql  .= $virgula." manutencao = '$this->manutencao' ";
       $virgula = ",";
       if(trim($this->manutencao) == null ){ 
         $this->erro_sql = " Campo Manutenção não informado.";
         $this->erro_campo = "manutencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->desctec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["desctec"])){ 
       $sql  .= $virgula." desctec = '$this->desctec' ";
       $virgula = ",";
       if(trim($this->desctec) == null ){ 
         $this->erro_sql = " Campo Descrição Técnica não informado.";
         $this->erro_campo = "desctec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->libcliente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["libcliente"])){ 
       $sql  .= $virgula." libcliente = '$this->libcliente' ";
       $virgula = ",";
       if(trim($this->libcliente) == null ){ 
         $this->erro_sql = " Campo Se item de menu está liberado para cliente não informado.";
         $this->erro_campo = "libcliente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_item!=null){
       $sql .= " id_item = $this->id_item";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->id_item));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["id_item"]) || $this->id_item != "")
             $resac = db_query("insert into db_acount values($acount,156,821,'".AddSlashes(pg_result($resaco,$conresaco,'id_item'))."','$this->id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["descricao"]) || $this->descricao != "")
             $resac = db_query("insert into db_acount values($acount,156,750,'".AddSlashes(pg_result($resaco,$conresaco,'descricao'))."','$this->descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["help"]) || $this->help != "")
             $resac = db_query("insert into db_acount values($acount,156,823,'".AddSlashes(pg_result($resaco,$conresaco,'help'))."','$this->help',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["funcao"]) || $this->funcao != "")
             $resac = db_query("insert into db_acount values($acount,156,824,'".AddSlashes(pg_result($resaco,$conresaco,'funcao'))."','$this->funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["itemativo"]) || $this->itemativo != "")
             $resac = db_query("insert into db_acount values($acount,156,825,'".AddSlashes(pg_result($resaco,$conresaco,'itemativo'))."','$this->itemativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["manutencao"]) || $this->manutencao != "")
             $resac = db_query("insert into db_acount values($acount,156,826,'".AddSlashes(pg_result($resaco,$conresaco,'manutencao'))."','$this->manutencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["desctec"]) || $this->desctec != "")
             $resac = db_query("insert into db_acount values($acount,156,827,'".AddSlashes(pg_result($resaco,$conresaco,'desctec'))."','$this->desctec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["libcliente"]) || $this->libcliente != "")
             $resac = db_query("insert into db_acount values($acount,156,8923,'".AddSlashes(pg_result($resaco,$conresaco,'libcliente'))."','$this->libcliente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do menu nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_item;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do menu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_item=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($id_item));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,821,'$id_item','E')");
           $resac  = db_query("insert into db_acount values($acount,156,821,'','".AddSlashes(pg_result($resaco,$iresaco,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,750,'','".AddSlashes(pg_result($resaco,$iresaco,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,823,'','".AddSlashes(pg_result($resaco,$iresaco,'help'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,824,'','".AddSlashes(pg_result($resaco,$iresaco,'funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,825,'','".AddSlashes(pg_result($resaco,$iresaco,'itemativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,826,'','".AddSlashes(pg_result($resaco,$iresaco,'manutencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,827,'','".AddSlashes(pg_result($resaco,$iresaco,'desctec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,156,8923,'','".AddSlashes(pg_result($resaco,$iresaco,'libcliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_itensmenu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_item != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_item = $id_item ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do menu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_item;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do menu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_item;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_itensmenu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $id_item=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_itensmenu ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_item!=null ){
         $sql2 .= " where db_itensmenu.id_item = $id_item "; 
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
   function sql_query_file ( $id_item=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_itensmenu ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_item!=null ){
         $sql2 .= " where db_itensmenu.id_item = $id_item "; 
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
    * Sql base que retorna os itens de menu, com seu itens filhos
    * @param  integer $iInstituicao
    * @param  integer $iModulo
    * @return string
    */
   function sql_queryArvoreMenus( $sCampos = null, $iIdItem = null, $iModulo = null ) {
                                                                                                         
     $sSql = " select distinct \n";
     
     if ( empty($sCampos) ) {
       
       $sSql.= "        db_menu.menusequencia,                                                             \n";
       $sSql.= "        case when db_modulos.id_item is null       then false else true  end as is_modulo, \n";
       $sSql.= "        case when db_menu.modulo = db_menu.id_item then true  else false end as is_raiz,   \n";
       $sSql.= "        db_menu.id_item       as id_parent,                                                \n";
       $sSql.= "        db_menu.id_item_filho as id_proprio,                                               \n";
       $sSql.= "        db_menu.modulo        as id_modulo,                                                \n";
       $sSql.= "        db_itensmenu.id_item as pai,                                                       \n";
       $sSql.= "        db_itensmenu.descricao                                                             \n";
     } else {
       $sSql.= $sCampos;
     }
     $sSql.= "   from db_itensmenu                                                                       \n";
     $sSql.= "        inner join db_menu         on db_menu.id_item_filho  = db_itensmenu.id_item        \n";
     $sSql.= "        left  join db_modulos      on db_modulos.id_item     = db_menu.modulo              \n";
     
     $sSql.= "  where db_itensmenu.itemativo = 1                                                         \n";
     $sSql.= "    and db_itensmenu.libcliente is true                                                    \n";
     
     if ($iIdItem != null) {
       $sSql.= "    and  db_menu.id_item_filho = {$iIdItem}                                              \n";
     }

     if ( !empty( $iModulo ) ) {
       $sSql .= "    and  db_modulos.id_item = {$iModulo}                                              \n";
     }
     
     if ( empty($sCampos) ) {
       $sSql.= "  order by id_modulo,id_parent, db_menu.menusequencia                                      \n";
     }
     
     return $sSql;
   }
   function sql_query_menus ( $id_item=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_itensmenu i ";
     $sql .= "      inner join db_menu m on m.id_item_filho = i.id_item ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_item!=null ){
         $sql2 .= " where db_itensmenu.id_item = $id_item "; 
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
   * SQL para busca dos menus principais de um módulo ( Ex.: CADASTROS / RELATÓRIOS / CONSULTAS / PROCEDIMENTOS )
   * @param string $id_item
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_menus_principais ( $id_item = null, $campos = "*", $ordem = null, $dbwhere = "" ) {
    
    $sSql = "select ";
    
    if ( $campos != "*" ) {
      
      $campos_sql = split( "#", $campos );
      $virgula    = "";
      
      for ( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {
        
        $sSql     .= $virgula.$campos_sql[$i];
        $virgula   = ",";
      }
    } else {
      $sSql .= $campos;
    }
    
    $sSql  .= " from db_itensmenu";
    $sSql  .= "      inner join db_menu    on db_menu.id_item_filho = db_itensmenu.id_item ";
    $sSql  .= "      inner join db_modulos on db_modulos.id_item    = db_menu.modulo ";
    $sql2   = "";
    
    if ( $dbwhere == "" ) {
      
      if ( $id_item != null ) {
        $sql2 .= " where db_itensmenu.id_item = $id_item ";
      }
    } else if ( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }
    
    $sSql .= $sql2;
    if ( $ordem != null ) {
      
      $sSql       .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {
        
        $sSql    .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }

    return $sSql;
  }
}
?>