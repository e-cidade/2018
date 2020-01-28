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
//CLASSE DA ENTIDADE registroprecomovimentacaoitens
class cl_registroprecomovimentacaoitens { 
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
   var $pc66_sequencial = 0; 
   var $pc66_registroprecomovimentacao = 0; 
   var $pc66_orcamforne = 0; 
   var $pc66_datainicial_dia = null; 
   var $pc66_datainicial_mes = null; 
   var $pc66_datainicial_ano = null; 
   var $pc66_datainicial = null; 
   var $pc66_datafinal_dia = null; 
   var $pc66_datafinal_mes = null; 
   var $pc66_datafinal_ano = null; 
   var $pc66_datafinal = null; 
   var $pc66_pcorcamitem = 0; 
   var $pc66_justificativa = null; 
   var $pc66_tipomovimentacao = 0; 
   var $pc66_solicitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc66_sequencial = int4 = Sequencial 
                 pc66_registroprecomovimentacao = int4 = Registro de Movimentação dos Preços 
                 pc66_orcamforne = int8 = Orcamento Fornecedor 
                 pc66_datainicial = date = Data Inicial 
                 pc66_datafinal = date = Data Final 
                 pc66_pcorcamitem = int4 = Licitação Item 
                 pc66_justificativa = text = Justificava 
                 pc66_tipomovimentacao = int4 = Código do Tipo de Movimentação 
                 pc66_solicitem = int4 = Item da Compilação 
                 ";
   //funcao construtor da classe 
   function cl_registroprecomovimentacaoitens() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecomovimentacaoitens"); 
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
       $this->pc66_sequencial = ($this->pc66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_sequencial"]:$this->pc66_sequencial);
       $this->pc66_registroprecomovimentacao = ($this->pc66_registroprecomovimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_registroprecomovimentacao"]:$this->pc66_registroprecomovimentacao);
       $this->pc66_orcamforne = ($this->pc66_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_orcamforne"]:$this->pc66_orcamforne);
       if($this->pc66_datainicial == ""){
         $this->pc66_datainicial_dia = ($this->pc66_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_dia"]:$this->pc66_datainicial_dia);
         $this->pc66_datainicial_mes = ($this->pc66_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_mes"]:$this->pc66_datainicial_mes);
         $this->pc66_datainicial_ano = ($this->pc66_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_ano"]:$this->pc66_datainicial_ano);
         if($this->pc66_datainicial_dia != ""){
            $this->pc66_datainicial = $this->pc66_datainicial_ano."-".$this->pc66_datainicial_mes."-".$this->pc66_datainicial_dia;
         }
       }
       if($this->pc66_datafinal == ""){
         $this->pc66_datafinal_dia = ($this->pc66_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_dia"]:$this->pc66_datafinal_dia);
         $this->pc66_datafinal_mes = ($this->pc66_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_mes"]:$this->pc66_datafinal_mes);
         $this->pc66_datafinal_ano = ($this->pc66_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_ano"]:$this->pc66_datafinal_ano);
         if($this->pc66_datafinal_dia != ""){
            $this->pc66_datafinal = $this->pc66_datafinal_ano."-".$this->pc66_datafinal_mes."-".$this->pc66_datafinal_dia;
         }
       }
       $this->pc66_pcorcamitem = ($this->pc66_pcorcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_pcorcamitem"]:$this->pc66_pcorcamitem);
       $this->pc66_justificativa = ($this->pc66_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_justificativa"]:$this->pc66_justificativa);
       $this->pc66_tipomovimentacao = ($this->pc66_tipomovimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_tipomovimentacao"]:$this->pc66_tipomovimentacao);
       $this->pc66_solicitem = ($this->pc66_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_solicitem"]:$this->pc66_solicitem);
     }else{
       $this->pc66_sequencial = ($this->pc66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc66_sequencial"]:$this->pc66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc66_sequencial){ 
      $this->atualizacampos();
     if($this->pc66_registroprecomovimentacao == null ){ 
       $this->erro_sql = " Campo Registro de Movimentação dos Preços nao Informado.";
       $this->erro_campo = "pc66_registroprecomovimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc66_orcamforne == null ){ 
       $this->pc66_orcamforne = "0";
     }
     if($this->pc66_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "pc66_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc66_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "pc66_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc66_pcorcamitem == null ){ 
       $this->erro_sql = " Campo Licitação Item nao Informado.";
       $this->erro_campo = "pc66_pcorcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc66_tipomovimentacao == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Movimentação nao Informado.";
       $this->erro_campo = "pc66_tipomovimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc66_solicitem == null ){ 
       $this->erro_sql = " Campo Item da Compilação nao Informado.";
       $this->erro_campo = "pc66_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc66_sequencial == "" || $pc66_sequencial == null ){
       $result = db_query("select nextval('registroprecomovimentacaoitens_pc66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecomovimentacaoitens_pc66_sequencial_seq do campo: pc66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecomovimentacaoitens_pc66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc66_sequencial)){
         $this->erro_sql = " Campo pc66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc66_sequencial = $pc66_sequencial; 
       }
     }
     if(($this->pc66_sequencial == null) || ($this->pc66_sequencial == "") ){ 
       $this->erro_sql = " Campo pc66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecomovimentacaoitens(
                                       pc66_sequencial 
                                      ,pc66_registroprecomovimentacao 
                                      ,pc66_orcamforne 
                                      ,pc66_datainicial 
                                      ,pc66_datafinal 
                                      ,pc66_pcorcamitem 
                                      ,pc66_justificativa 
                                      ,pc66_tipomovimentacao 
                                      ,pc66_solicitem 
                       )
                values (
                                $this->pc66_sequencial 
                               ,$this->pc66_registroprecomovimentacao 
                               ,$this->pc66_orcamforne 
                               ,".($this->pc66_datainicial == "null" || $this->pc66_datainicial == ""?"null":"'".$this->pc66_datainicial."'")." 
                               ,".($this->pc66_datafinal == "null" || $this->pc66_datafinal == ""?"null":"'".$this->pc66_datafinal."'")." 
                               ,$this->pc66_pcorcamitem 
                               ,'$this->pc66_justificativa' 
                               ,$this->pc66_tipomovimentacao 
                               ,$this->pc66_solicitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Movimentação dos Preços por Item  ($this->pc66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Movimentação dos Preços por Item  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Movimentação dos Preços por Item  ($this->pc66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc66_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15284,'$this->pc66_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2694,15284,'','".AddSlashes(pg_result($resaco,0,'pc66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15285,'','".AddSlashes(pg_result($resaco,0,'pc66_registroprecomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15286,'','".AddSlashes(pg_result($resaco,0,'pc66_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15287,'','".AddSlashes(pg_result($resaco,0,'pc66_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15288,'','".AddSlashes(pg_result($resaco,0,'pc66_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15289,'','".AddSlashes(pg_result($resaco,0,'pc66_pcorcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15302,'','".AddSlashes(pg_result($resaco,0,'pc66_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,15293,'','".AddSlashes(pg_result($resaco,0,'pc66_tipomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2694,18206,'','".AddSlashes(pg_result($resaco,0,'pc66_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecomovimentacaoitens set ";
     $virgula = "";
     if(trim($this->pc66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_sequencial"])){ 
       $sql  .= $virgula." pc66_sequencial = $this->pc66_sequencial ";
       $virgula = ",";
       if(trim($this->pc66_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc66_registroprecomovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_registroprecomovimentacao"])){ 
       $sql  .= $virgula." pc66_registroprecomovimentacao = $this->pc66_registroprecomovimentacao ";
       $virgula = ",";
       if(trim($this->pc66_registroprecomovimentacao) == null ){ 
         $this->erro_sql = " Campo Registro de Movimentação dos Preços nao Informado.";
         $this->erro_campo = "pc66_registroprecomovimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc66_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_orcamforne"])){ 
        if(trim($this->pc66_orcamforne)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc66_orcamforne"])){ 
           $this->pc66_orcamforne = "0" ; 
        } 
       $sql  .= $virgula." pc66_orcamforne = $this->pc66_orcamforne ";
       $virgula = ",";
     }
     if(trim($this->pc66_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." pc66_datainicial = '$this->pc66_datainicial' ";
       $virgula = ",";
       if(trim($this->pc66_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "pc66_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_datainicial_dia"])){ 
         $sql  .= $virgula." pc66_datainicial = null ";
         $virgula = ",";
         if(trim($this->pc66_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "pc66_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc66_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." pc66_datafinal = '$this->pc66_datafinal' ";
       $virgula = ",";
       if(trim($this->pc66_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "pc66_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_datafinal_dia"])){ 
         $sql  .= $virgula." pc66_datafinal = null ";
         $virgula = ",";
         if(trim($this->pc66_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "pc66_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc66_pcorcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_pcorcamitem"])){ 
       $sql  .= $virgula." pc66_pcorcamitem = $this->pc66_pcorcamitem ";
       $virgula = ",";
       if(trim($this->pc66_pcorcamitem) == null ){ 
         $this->erro_sql = " Campo Licitação Item nao Informado.";
         $this->erro_campo = "pc66_pcorcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc66_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_justificativa"])){ 
       $sql  .= $virgula." pc66_justificativa = '$this->pc66_justificativa' ";
       $virgula = ",";
     }
     if(trim($this->pc66_tipomovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_tipomovimentacao"])){ 
       $sql  .= $virgula." pc66_tipomovimentacao = $this->pc66_tipomovimentacao ";
       $virgula = ",";
       if(trim($this->pc66_tipomovimentacao) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Movimentação nao Informado.";
         $this->erro_campo = "pc66_tipomovimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc66_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc66_solicitem"])){ 
       $sql  .= $virgula." pc66_solicitem = $this->pc66_solicitem ";
       $virgula = ",";
       if(trim($this->pc66_solicitem) == null ){ 
         $this->erro_sql = " Campo Item da Compilação nao Informado.";
         $this->erro_campo = "pc66_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc66_sequencial!=null){
       $sql .= " pc66_sequencial = $this->pc66_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc66_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15284,'$this->pc66_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_sequencial"]) || $this->pc66_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2694,15284,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_sequencial'))."','$this->pc66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_registroprecomovimentacao"]) || $this->pc66_registroprecomovimentacao != "")
           $resac = db_query("insert into db_acount values($acount,2694,15285,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_registroprecomovimentacao'))."','$this->pc66_registroprecomovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_orcamforne"]) || $this->pc66_orcamforne != "")
           $resac = db_query("insert into db_acount values($acount,2694,15286,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_orcamforne'))."','$this->pc66_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_datainicial"]) || $this->pc66_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2694,15287,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_datainicial'))."','$this->pc66_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_datafinal"]) || $this->pc66_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2694,15288,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_datafinal'))."','$this->pc66_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_pcorcamitem"]) || $this->pc66_pcorcamitem != "")
           $resac = db_query("insert into db_acount values($acount,2694,15289,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_pcorcamitem'))."','$this->pc66_pcorcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_justificativa"]) || $this->pc66_justificativa != "")
           $resac = db_query("insert into db_acount values($acount,2694,15302,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_justificativa'))."','$this->pc66_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_tipomovimentacao"]) || $this->pc66_tipomovimentacao != "")
           $resac = db_query("insert into db_acount values($acount,2694,15293,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_tipomovimentacao'))."','$this->pc66_tipomovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc66_solicitem"]) || $this->pc66_solicitem != "")
           $resac = db_query("insert into db_acount values($acount,2694,18206,'".AddSlashes(pg_result($resaco,$conresaco,'pc66_solicitem'))."','$this->pc66_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Movimentação dos Preços por Item  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Movimentação dos Preços por Item  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc66_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc66_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15284,'$pc66_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2694,15284,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15285,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_registroprecomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15286,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15287,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15288,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15289,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_pcorcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15302,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,15293,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_tipomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2694,18206,'','".AddSlashes(pg_result($resaco,$iresaco,'pc66_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecomovimentacaoitens
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc66_sequencial = $pc66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Movimentação dos Preços por Item  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Movimentação dos Preços por Item  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecomovimentacaoitens";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecomovimentacaoitens ";
     $sql .= "      left  join pcorcamforne  on  pcorcamforne.pc21_orcamforne = registroprecomovimentacaoitens.pc66_orcamforne";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = registroprecomovimentacaoitens.pc66_solicitem";
     $sql .= "      inner join tipomovimentacaoregistropreco  on  tipomovimentacaoregistropreco.l33_sequencial = registroprecomovimentacaoitens.pc66_tipomovimentacao";
     $sql .= "      inner join registroprecomovimentacao  on  registroprecomovimentacao.pc58_sequencial = registroprecomovimentacaoitens.pc66_registroprecomovimentacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join solicita  as a on   a.pc10_numero = registroprecomovimentacao.pc58_solicita";
     $sql2 = "";
     if($dbwhere==""){
       if($pc66_sequencial!=null ){
         $sql2 .= " where registroprecomovimentacaoitens.pc66_sequencial = $pc66_sequencial "; 
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
   function sql_query_file ( $pc66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecomovimentacaoitens ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc66_sequencial!=null ){
         $sql2 .= " where registroprecomovimentacaoitens.pc66_sequencial = $pc66_sequencial "; 
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
   function sql_query_orcamento ( $pc66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecomovimentacaoitens ";
     $sql .= "      left  join pcorcamforne  on  pcorcamforne.pc21_orcamforne = registroprecomovimentacaoitens.pc66_orcamforne";
     $sql .= "      inner join tipomovimentacaoregistropreco  on  tipomovimentacaoregistropreco.l33_sequencial = registroprecomovimentacaoitens.pc66_tipomovimentacao";
     $sql .= "      inner join registroprecomovimentacao  on  registroprecomovimentacao.pc58_sequencial = registroprecomovimentacaoitens.pc66_registroprecomovimentacao";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcamitem  on  pc22_orcamitem   = pc66_pcorcamitem";
     $sql .= "      inner join pcorcamitemlic   on  pc22_orcamitem   = pc26_orcamitem";
     $sql .= "      inner join liclicitem       on pc26_liclicitem   = l21_codigo";
     $sql .= "      inner join pcprocitem      on l21_codpcprocitem = pc81_codprocitem";
     $sql .= "      inner join solicitem        on pc81_solicitem    = pc11_codigo";
     $sql .= "      inner join solicitempcmater on pc11_codigo       = pc16_solicitem"; 
     $sql .= "      inner join pcmater          on pc16_codmater     = pc01_codmater"; 
     $sql .= "      inner join pcorcam          on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join db_usuarios  on  pc58_usuario  = id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc66_sequencial!=null ){
         $sql2 .= " where registroprecomovimentacaoitens.pc66_sequencial = $pc66_sequencial "; 
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