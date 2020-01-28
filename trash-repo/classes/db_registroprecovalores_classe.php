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

//MODULO: compras
//CLASSE DA ENTIDADE registroprecovalores
class cl_registroprecovalores { 
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
   var $pc56_sequencial = 0; 
   var $pc56_orcamforne = 0; 
   var $pc56_orcamitem = 0; 
   var $pc56_valorunitario = 0; 
   var $pc56_ativo = 'f'; 
   var $pc56_pcorcamval = 0; 
   var $pc56_solicitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc56_sequencial = int4 = Sequencial 
                 pc56_orcamforne = int8 = Código do Fornecedor 
                 pc56_orcamitem = int4 = Código do Item 
                 pc56_valorunitario = float8 = Valor Unitário 
                 pc56_ativo = bool = Ativo 
                 pc56_pcorcamval = int4 = Código do Item do Orçamento 
                 pc56_solicitem = int4 = Item da Compilação 
                 ";
   //funcao construtor da classe 
   function cl_registroprecovalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecovalores"); 
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
       $this->pc56_sequencial = ($this->pc56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_sequencial"]:$this->pc56_sequencial);
       $this->pc56_orcamforne = ($this->pc56_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_orcamforne"]:$this->pc56_orcamforne);
       $this->pc56_orcamitem = ($this->pc56_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_orcamitem"]:$this->pc56_orcamitem);
       $this->pc56_valorunitario = ($this->pc56_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_valorunitario"]:$this->pc56_valorunitario);
       $this->pc56_ativo = ($this->pc56_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc56_ativo"]:$this->pc56_ativo);
       $this->pc56_pcorcamval = ($this->pc56_pcorcamval == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_pcorcamval"]:$this->pc56_pcorcamval);
       $this->pc56_solicitem = ($this->pc56_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_solicitem"]:$this->pc56_solicitem);
     }else{
       $this->pc56_sequencial = ($this->pc56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc56_sequencial"]:$this->pc56_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc56_sequencial){ 
      $this->atualizacampos();
     if($this->pc56_orcamforne == null ){ 
       $this->erro_sql = " Campo Código do Fornecedor nao Informado.";
       $this->erro_campo = "pc56_orcamforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc56_orcamitem == null ){ 
       $this->erro_sql = " Campo Código do Item nao Informado.";
       $this->erro_campo = "pc56_orcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc56_valorunitario == null ){ 
       $this->erro_sql = " Campo Valor Unitário nao Informado.";
       $this->erro_campo = "pc56_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc56_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "pc56_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc56_pcorcamval == null ){ 
       $this->pc56_pcorcamval = "0";
     }
     if($this->pc56_solicitem == null ){ 
       $this->erro_sql = " Campo Item da Compilação nao Informado.";
       $this->erro_campo = "pc56_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc56_sequencial == "" || $pc56_sequencial == null ){
       $result = db_query("select nextval('registroprecovalores_pc56_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecovalores_pc56_sequencial_seq do campo: pc56_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc56_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecovalores_pc56_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc56_sequencial)){
         $this->erro_sql = " Campo pc56_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc56_sequencial = $pc56_sequencial; 
       }
     }
     if(($this->pc56_sequencial == null) || ($this->pc56_sequencial == "") ){ 
       $this->erro_sql = " Campo pc56_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecovalores(
                                       pc56_sequencial 
                                      ,pc56_orcamforne 
                                      ,pc56_orcamitem 
                                      ,pc56_valorunitario 
                                      ,pc56_ativo 
                                      ,pc56_pcorcamval 
                                      ,pc56_solicitem 
                       )
                values (
                                $this->pc56_sequencial 
                               ,$this->pc56_orcamforne 
                               ,$this->pc56_orcamitem 
                               ,$this->pc56_valorunitario 
                               ,'$this->pc56_ativo' 
                               ,$this->pc56_pcorcamval 
                               ,$this->pc56_solicitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores de Julgamento do Registro Preço ($this->pc56_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores de Julgamento do Registro Preço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores de Julgamento do Registro Preço ($this->pc56_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc56_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc56_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15278,'$this->pc56_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2693,15278,'','".AddSlashes(pg_result($resaco,0,'pc56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,15279,'','".AddSlashes(pg_result($resaco,0,'pc56_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,15280,'','".AddSlashes(pg_result($resaco,0,'pc56_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,15281,'','".AddSlashes(pg_result($resaco,0,'pc56_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,15282,'','".AddSlashes(pg_result($resaco,0,'pc56_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,15283,'','".AddSlashes(pg_result($resaco,0,'pc56_pcorcamval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2693,18205,'','".AddSlashes(pg_result($resaco,0,'pc56_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc56_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecovalores set ";
     $virgula = "";
     if(trim($this->pc56_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_sequencial"])){ 
       $sql  .= $virgula." pc56_sequencial = $this->pc56_sequencial ";
       $virgula = ",";
       if(trim($this->pc56_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc56_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc56_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_orcamforne"])){ 
       $sql  .= $virgula." pc56_orcamforne = $this->pc56_orcamforne ";
       $virgula = ",";
       if(trim($this->pc56_orcamforne) == null ){ 
         $this->erro_sql = " Campo Código do Fornecedor nao Informado.";
         $this->erro_campo = "pc56_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc56_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_orcamitem"])){ 
       $sql  .= $virgula." pc56_orcamitem = $this->pc56_orcamitem ";
       $virgula = ",";
       if(trim($this->pc56_orcamitem) == null ){ 
         $this->erro_sql = " Campo Código do Item nao Informado.";
         $this->erro_campo = "pc56_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc56_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_valorunitario"])){ 
       $sql  .= $virgula." pc56_valorunitario = $this->pc56_valorunitario ";
       $virgula = ",";
       if(trim($this->pc56_valorunitario) == null ){ 
         $this->erro_sql = " Campo Valor Unitário nao Informado.";
         $this->erro_campo = "pc56_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc56_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_ativo"])){ 
       $sql  .= $virgula." pc56_ativo = '$this->pc56_ativo' ";
       $virgula = ",";
       if(trim($this->pc56_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "pc56_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc56_pcorcamval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_pcorcamval"])){ 
        if(trim($this->pc56_pcorcamval)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc56_pcorcamval"])){ 
           $this->pc56_pcorcamval = "0" ; 
        } 
       $sql  .= $virgula." pc56_pcorcamval = $this->pc56_pcorcamval ";
       $virgula = ",";
     }
     if(trim($this->pc56_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc56_solicitem"])){ 
       $sql  .= $virgula." pc56_solicitem = $this->pc56_solicitem ";
       $virgula = ",";
       if(trim($this->pc56_solicitem) == null ){ 
         $this->erro_sql = " Campo Item da Compilação nao Informado.";
         $this->erro_campo = "pc56_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc56_sequencial!=null){
       $sql .= " pc56_sequencial = $this->pc56_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc56_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15278,'$this->pc56_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_sequencial"]) || $this->pc56_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2693,15278,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_sequencial'))."','$this->pc56_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_orcamforne"]) || $this->pc56_orcamforne != "")
           $resac = db_query("insert into db_acount values($acount,2693,15279,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_orcamforne'))."','$this->pc56_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_orcamitem"]) || $this->pc56_orcamitem != "")
           $resac = db_query("insert into db_acount values($acount,2693,15280,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_orcamitem'))."','$this->pc56_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_valorunitario"]) || $this->pc56_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,2693,15281,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_valorunitario'))."','$this->pc56_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_ativo"]) || $this->pc56_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2693,15282,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_ativo'))."','$this->pc56_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_pcorcamval"]) || $this->pc56_pcorcamval != "")
           $resac = db_query("insert into db_acount values($acount,2693,15283,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_pcorcamval'))."','$this->pc56_pcorcamval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc56_solicitem"]) || $this->pc56_solicitem != "")
           $resac = db_query("insert into db_acount values($acount,2693,18205,'".AddSlashes(pg_result($resaco,$conresaco,'pc56_solicitem'))."','$this->pc56_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores de Julgamento do Registro Preço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores de Julgamento do Registro Preço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc56_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc56_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15278,'$pc56_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2693,15278,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,15279,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,15280,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,15281,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,15282,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,15283,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_pcorcamval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2693,18205,'','".AddSlashes(pg_result($resaco,$iresaco,'pc56_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecovalores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc56_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc56_sequencial = $pc56_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores de Julgamento do Registro Preço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores de Julgamento do Registro Preço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc56_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecovalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc56_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecovalores ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = registroprecovalores.pc56_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = registroprecovalores.pc56_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = registroprecovalores.pc56_solicitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc56_sequencial!=null ){
         $sql2 .= " where registroprecovalores.pc56_sequencial = $pc56_sequencial "; 
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
   function sql_query_file ( $pc56_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecovalores ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc56_sequencial!=null ){
         $sql2 .= " where registroprecovalores.pc56_sequencial = $pc56_sequencial "; 
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