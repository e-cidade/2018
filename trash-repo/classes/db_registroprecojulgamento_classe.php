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
//CLASSE DA ENTIDADE registroprecojulgamento
class cl_registroprecojulgamento { 
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
   var $pc65_sequencial = 0; 
   var $pc65_orcamitem = 0; 
   var $pc65_orcamforne = 0; 
   var $pc65_pontuacao = 0; 
   var $pc65_ativo = 'f'; 
   var $pc65_orcamjulg = 0; 
   var $pc65_solicitem = 0; 
   var $pc65_valorunitario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc65_sequencial = int4 = Sequencial 
                 pc65_orcamitem = int4 = Código do Item 
                 pc65_orcamforne = int8 = Código do Fornecedor 
                 pc65_pontuacao = int4 = Pontuação 
                 pc65_ativo = bool = Ativo 
                 pc65_orcamjulg = int4 = Código do Julgamento 
                 pc65_solicitem = int4 = Item da Compilação 
                 pc65_valorunitario = int4 = Valor Unitário 
                 ";
   //funcao construtor da classe 
   function cl_registroprecojulgamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecojulgamento"); 
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
       $this->pc65_sequencial = ($this->pc65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_sequencial"]:$this->pc65_sequencial);
       $this->pc65_orcamitem = ($this->pc65_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_orcamitem"]:$this->pc65_orcamitem);
       $this->pc65_orcamforne = ($this->pc65_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_orcamforne"]:$this->pc65_orcamforne);
       $this->pc65_pontuacao = ($this->pc65_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_pontuacao"]:$this->pc65_pontuacao);
       $this->pc65_ativo = ($this->pc65_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc65_ativo"]:$this->pc65_ativo);
       $this->pc65_orcamjulg = ($this->pc65_orcamjulg == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_orcamjulg"]:$this->pc65_orcamjulg);
       $this->pc65_solicitem = ($this->pc65_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_solicitem"]:$this->pc65_solicitem);
       $this->pc65_valorunitario = ($this->pc65_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_valorunitario"]:$this->pc65_valorunitario);
     }else{
       $this->pc65_sequencial = ($this->pc65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc65_sequencial"]:$this->pc65_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc65_sequencial){ 
      $this->atualizacampos();
     if($this->pc65_orcamitem == null ){ 
       $this->erro_sql = " Campo Código do Item nao Informado.";
       $this->erro_campo = "pc65_orcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc65_orcamforne == null ){ 
       $this->erro_sql = " Campo Código do Fornecedor nao Informado.";
       $this->erro_campo = "pc65_orcamforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc65_pontuacao == null ){ 
       $this->erro_sql = " Campo Pontuação nao Informado.";
       $this->erro_campo = "pc65_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc65_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "pc65_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc65_orcamjulg == null ){ 
       $this->pc65_orcamjulg = "0";
     }
     if($this->pc65_solicitem == null ){ 
       $this->erro_sql = " Campo Item da Compilação nao Informado.";
       $this->erro_campo = "pc65_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc65_valorunitario == null ){ 
       $this->erro_sql = " Campo Valor Unitário nao Informado.";
       $this->erro_campo = "pc65_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc65_sequencial == "" || $pc65_sequencial == null ){
       $result = db_query("select nextval('registroprecojulgamento_pc65_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecojulgamento_pc65_sequencial_seq do campo: pc65_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc65_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecojulgamento_pc65_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc65_sequencial)){
         $this->erro_sql = " Campo pc65_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc65_sequencial = $pc65_sequencial; 
       }
     }
     if(($this->pc65_sequencial == null) || ($this->pc65_sequencial == "") ){ 
       $this->erro_sql = " Campo pc65_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecojulgamento(
                                       pc65_sequencial 
                                      ,pc65_orcamitem 
                                      ,pc65_orcamforne 
                                      ,pc65_pontuacao 
                                      ,pc65_ativo 
                                      ,pc65_orcamjulg 
                                      ,pc65_solicitem 
                                      ,pc65_valorunitario 
                       )
                values (
                                $this->pc65_sequencial 
                               ,$this->pc65_orcamitem 
                               ,$this->pc65_orcamforne 
                               ,$this->pc65_pontuacao 
                               ,'$this->pc65_ativo' 
                               ,$this->pc65_orcamjulg 
                               ,$this->pc65_solicitem 
                               ,$this->pc65_valorunitario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Julgamentos do Registro do Preço ($this->pc65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Julgamentos do Registro do Preço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Julgamentos do Registro do Preço ($this->pc65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc65_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc65_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15272,'$this->pc65_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2692,15272,'','".AddSlashes(pg_result($resaco,0,'pc65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,15273,'','".AddSlashes(pg_result($resaco,0,'pc65_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,15274,'','".AddSlashes(pg_result($resaco,0,'pc65_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,15275,'','".AddSlashes(pg_result($resaco,0,'pc65_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,15276,'','".AddSlashes(pg_result($resaco,0,'pc65_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,15277,'','".AddSlashes(pg_result($resaco,0,'pc65_orcamjulg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,18202,'','".AddSlashes(pg_result($resaco,0,'pc65_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2692,18203,'','".AddSlashes(pg_result($resaco,0,'pc65_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc65_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecojulgamento set ";
     $virgula = "";
     if(trim($this->pc65_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_sequencial"])){ 
       $sql  .= $virgula." pc65_sequencial = $this->pc65_sequencial ";
       $virgula = ",";
       if(trim($this->pc65_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc65_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamitem"])){ 
       $sql  .= $virgula." pc65_orcamitem = $this->pc65_orcamitem ";
       $virgula = ",";
       if(trim($this->pc65_orcamitem) == null ){ 
         $this->erro_sql = " Campo Código do Item nao Informado.";
         $this->erro_campo = "pc65_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamforne"])){ 
       $sql  .= $virgula." pc65_orcamforne = $this->pc65_orcamforne ";
       $virgula = ",";
       if(trim($this->pc65_orcamforne) == null ){ 
         $this->erro_sql = " Campo Código do Fornecedor nao Informado.";
         $this->erro_campo = "pc65_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_pontuacao"])){ 
       $sql  .= $virgula." pc65_pontuacao = $this->pc65_pontuacao ";
       $virgula = ",";
       if(trim($this->pc65_pontuacao) == null ){ 
         $this->erro_sql = " Campo Pontuação nao Informado.";
         $this->erro_campo = "pc65_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_ativo"])){ 
       $sql  .= $virgula." pc65_ativo = '$this->pc65_ativo' ";
       $virgula = ",";
       if(trim($this->pc65_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "pc65_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_orcamjulg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamjulg"])){ 
        if(trim($this->pc65_orcamjulg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamjulg"])){ 
           $this->pc65_orcamjulg = "0" ; 
        } 
       $sql  .= $virgula." pc65_orcamjulg = $this->pc65_orcamjulg ";
       $virgula = ",";
     }
     if(trim($this->pc65_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_solicitem"])){ 
       $sql  .= $virgula." pc65_solicitem = $this->pc65_solicitem ";
       $virgula = ",";
       if(trim($this->pc65_solicitem) == null ){ 
         $this->erro_sql = " Campo Item da Compilação nao Informado.";
         $this->erro_campo = "pc65_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc65_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc65_valorunitario"])){ 
       $sql  .= $virgula." pc65_valorunitario = $this->pc65_valorunitario ";
       $virgula = ",";
       if(trim($this->pc65_valorunitario) == null ){ 
         $this->erro_sql = " Campo Valor Unitário nao Informado.";
         $this->erro_campo = "pc65_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc65_sequencial!=null){
       $sql .= " pc65_sequencial = $this->pc65_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc65_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15272,'$this->pc65_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_sequencial"]) || $this->pc65_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2692,15272,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_sequencial'))."','$this->pc65_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamitem"]) || $this->pc65_orcamitem != "")
           $resac = db_query("insert into db_acount values($acount,2692,15273,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_orcamitem'))."','$this->pc65_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamforne"]) || $this->pc65_orcamforne != "")
           $resac = db_query("insert into db_acount values($acount,2692,15274,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_orcamforne'))."','$this->pc65_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_pontuacao"]) || $this->pc65_pontuacao != "")
           $resac = db_query("insert into db_acount values($acount,2692,15275,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_pontuacao'))."','$this->pc65_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_ativo"]) || $this->pc65_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2692,15276,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_ativo'))."','$this->pc65_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_orcamjulg"]) || $this->pc65_orcamjulg != "")
           $resac = db_query("insert into db_acount values($acount,2692,15277,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_orcamjulg'))."','$this->pc65_orcamjulg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_solicitem"]) || $this->pc65_solicitem != "")
           $resac = db_query("insert into db_acount values($acount,2692,18202,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_solicitem'))."','$this->pc65_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc65_valorunitario"]) || $this->pc65_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,2692,18203,'".AddSlashes(pg_result($resaco,$conresaco,'pc65_valorunitario'))."','$this->pc65_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Julgamentos do Registro do Preço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Julgamentos do Registro do Preço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc65_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc65_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15272,'$pc65_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2692,15272,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,15273,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,15274,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,15275,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,15276,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,15277,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_orcamjulg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,18202,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2692,18203,'','".AddSlashes(pg_result($resaco,$iresaco,'pc65_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecojulgamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc65_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc65_sequencial = $pc65_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Julgamentos do Registro do Preço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Julgamentos do Registro do Preço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc65_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecojulgamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecojulgamento ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = registroprecojulgamento.pc65_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = registroprecojulgamento.pc65_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = registroprecojulgamento.pc65_solicitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc65_sequencial!=null ){
         $sql2 .= " where registroprecojulgamento.pc65_sequencial = $pc65_sequencial "; 
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
   function sql_query_file ( $pc65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecojulgamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc65_sequencial!=null ){
         $sql2 .= " where registroprecojulgamento.pc65_sequencial = $pc65_sequencial "; 
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