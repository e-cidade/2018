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

//MODULO: Caixa
//CLASSE DA ENTIDADE tabplansaldorecurso
class cl_tabplansaldorecurso { 
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
   var $k111_sequencial = 0; 
   var $k111_tabplan = 0; 
   var $k111_recurso = 0; 
   var $k111_dataimplantacao_dia = null; 
   var $k111_dataimplantacao_mes = null; 
   var $k111_dataimplantacao_ano = null; 
   var $k111_dataimplantacao = null; 
   var $k111_creditoinicial = 0; 
   var $k111_debitoinicial = 0; 
   var $k111_anousu = 0; 
   var $k111_dataatualizacao_dia = null; 
   var $k111_dataatualizacao_mes = null; 
   var $k111_dataatualizacao_ano = null; 
   var $k111_dataatualizacao = null; 
   var $k111_creditoatualizado = 0; 
   var $k111_debitoatualizado = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k111_sequencial = int4 = Código Sequencial 
                 k111_tabplan = int4 = Receita Extra 
                 k111_recurso = int4 = Recurso 
                 k111_dataimplantacao = date = Data da Implantação do Saldo 
                 k111_creditoinicial = float8 = Crédito Inicial da Conta 
                 k111_debitoinicial = float8 = Débito Inicial 
                 k111_anousu = int4 = Ano 
                 k111_dataatualizacao = date = Data da Atualização do Saldo 
                 k111_creditoatualizado = float4 = Crédito Atualizado 
                 k111_debitoatualizado = float8 = Débito Atualizado 
                 ";
   //funcao construtor da classe 
   function cl_tabplansaldorecurso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabplansaldorecurso"); 
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
       $this->k111_sequencial = ($this->k111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_sequencial"]:$this->k111_sequencial);
       $this->k111_tabplan = ($this->k111_tabplan == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_tabplan"]:$this->k111_tabplan);
       $this->k111_recurso = ($this->k111_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_recurso"]:$this->k111_recurso);
       if($this->k111_dataimplantacao == ""){
         $this->k111_dataimplantacao_dia = ($this->k111_dataimplantacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_dia"]:$this->k111_dataimplantacao_dia);
         $this->k111_dataimplantacao_mes = ($this->k111_dataimplantacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_mes"]:$this->k111_dataimplantacao_mes);
         $this->k111_dataimplantacao_ano = ($this->k111_dataimplantacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_ano"]:$this->k111_dataimplantacao_ano);
         if($this->k111_dataimplantacao_dia != ""){
            $this->k111_dataimplantacao = $this->k111_dataimplantacao_ano."-".$this->k111_dataimplantacao_mes."-".$this->k111_dataimplantacao_dia;
         }
       }
       $this->k111_creditoinicial = ($this->k111_creditoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_creditoinicial"]:$this->k111_creditoinicial);
       $this->k111_debitoinicial = ($this->k111_debitoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_debitoinicial"]:$this->k111_debitoinicial);
       $this->k111_anousu = ($this->k111_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_anousu"]:$this->k111_anousu);
       if($this->k111_dataatualizacao == ""){
         $this->k111_dataatualizacao_dia = ($this->k111_dataatualizacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_dia"]:$this->k111_dataatualizacao_dia);
         $this->k111_dataatualizacao_mes = ($this->k111_dataatualizacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_mes"]:$this->k111_dataatualizacao_mes);
         $this->k111_dataatualizacao_ano = ($this->k111_dataatualizacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_ano"]:$this->k111_dataatualizacao_ano);
         if($this->k111_dataatualizacao_dia != ""){
            $this->k111_dataatualizacao = $this->k111_dataatualizacao_ano."-".$this->k111_dataatualizacao_mes."-".$this->k111_dataatualizacao_dia;
         }
       }
       $this->k111_creditoatualizado = ($this->k111_creditoatualizado == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_creditoatualizado"]:$this->k111_creditoatualizado);
       $this->k111_debitoatualizado = ($this->k111_debitoatualizado == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_debitoatualizado"]:$this->k111_debitoatualizado);
     }else{
       $this->k111_sequencial = ($this->k111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k111_sequencial"]:$this->k111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k111_sequencial){ 
      $this->atualizacampos();
     if($this->k111_tabplan == null ){ 
       $this->erro_sql = " Campo Receita Extra nao Informado.";
       $this->erro_campo = "k111_tabplan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "k111_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_dataimplantacao == null ){ 
       $this->erro_sql = " Campo Data da Implantação do Saldo nao Informado.";
       $this->erro_campo = "k111_dataimplantacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_creditoinicial == null ){ 
       $this->erro_sql = " Campo Crédito Inicial da Conta nao Informado.";
       $this->erro_campo = "k111_creditoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_debitoinicial == null ){ 
       $this->erro_sql = " Campo Débito Inicial nao Informado.";
       $this->erro_campo = "k111_debitoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "k111_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_dataatualizacao == null ){ 
       $this->erro_sql = " Campo Data da Atualização do Saldo nao Informado.";
       $this->erro_campo = "k111_dataatualizacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_creditoatualizado == null ){ 
       $this->erro_sql = " Campo Crédito Atualizado nao Informado.";
       $this->erro_campo = "k111_creditoatualizado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k111_debitoatualizado == null ){ 
       $this->erro_sql = " Campo Débito Atualizado nao Informado.";
       $this->erro_campo = "k111_debitoatualizado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k111_sequencial == "" || $k111_sequencial == null ){
       $result = db_query("select nextval('tabplansaldorecurso_k111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabplansaldorecurso_k111_sequencial_seq do campo: k111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabplansaldorecurso_k111_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k111_sequencial)){
         $this->erro_sql = " Campo k111_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k111_sequencial = $k111_sequencial; 
       }
     }
     if(($this->k111_sequencial == null) || ($this->k111_sequencial == "") ){ 
       $this->erro_sql = " Campo k111_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabplansaldorecurso(
                                       k111_sequencial 
                                      ,k111_tabplan 
                                      ,k111_recurso 
                                      ,k111_dataimplantacao 
                                      ,k111_creditoinicial 
                                      ,k111_debitoinicial 
                                      ,k111_anousu 
                                      ,k111_dataatualizacao 
                                      ,k111_creditoatualizado 
                                      ,k111_debitoatualizado 
                       )
                values (
                                $this->k111_sequencial 
                               ,$this->k111_tabplan 
                               ,$this->k111_recurso 
                               ,".($this->k111_dataimplantacao == "null" || $this->k111_dataimplantacao == ""?"null":"'".$this->k111_dataimplantacao."'")." 
                               ,$this->k111_creditoinicial 
                               ,$this->k111_debitoinicial 
                               ,$this->k111_anousu 
                               ,".($this->k111_dataatualizacao == "null" || $this->k111_dataatualizacao == ""?"null":"'".$this->k111_dataatualizacao."'")." 
                               ,$this->k111_creditoatualizado 
                               ,$this->k111_debitoatualizado 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saldos dos recursos ($this->k111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldos dos recursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saldos dos recursos ($this->k111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k111_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k111_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14522,'$this->k111_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2559,14522,'','".AddSlashes(pg_result($resaco,0,'k111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14523,'','".AddSlashes(pg_result($resaco,0,'k111_tabplan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14524,'','".AddSlashes(pg_result($resaco,0,'k111_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14525,'','".AddSlashes(pg_result($resaco,0,'k111_dataimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14526,'','".AddSlashes(pg_result($resaco,0,'k111_creditoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14527,'','".AddSlashes(pg_result($resaco,0,'k111_debitoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14528,'','".AddSlashes(pg_result($resaco,0,'k111_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14529,'','".AddSlashes(pg_result($resaco,0,'k111_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14531,'','".AddSlashes(pg_result($resaco,0,'k111_creditoatualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2559,14530,'','".AddSlashes(pg_result($resaco,0,'k111_debitoatualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabplansaldorecurso set ";
     $virgula = "";
     if(trim($this->k111_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_sequencial"])){ 
       $sql  .= $virgula." k111_sequencial = $this->k111_sequencial ";
       $virgula = ",";
       if(trim($this->k111_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "k111_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_tabplan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_tabplan"])){ 
       $sql  .= $virgula." k111_tabplan = $this->k111_tabplan ";
       $virgula = ",";
       if(trim($this->k111_tabplan) == null ){ 
         $this->erro_sql = " Campo Receita Extra nao Informado.";
         $this->erro_campo = "k111_tabplan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_recurso"])){ 
       $sql  .= $virgula." k111_recurso = $this->k111_recurso ";
       $virgula = ",";
       if(trim($this->k111_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "k111_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_dataimplantacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_dia"] !="") ){ 
       $sql  .= $virgula." k111_dataimplantacao = '$this->k111_dataimplantacao' ";
       $virgula = ",";
       if(trim($this->k111_dataimplantacao) == null ){ 
         $this->erro_sql = " Campo Data da Implantação do Saldo nao Informado.";
         $this->erro_campo = "k111_dataimplantacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao_dia"])){ 
         $sql  .= $virgula." k111_dataimplantacao = null ";
         $virgula = ",";
         if(trim($this->k111_dataimplantacao) == null ){ 
           $this->erro_sql = " Campo Data da Implantação do Saldo nao Informado.";
           $this->erro_campo = "k111_dataimplantacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k111_creditoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_creditoinicial"])){ 
       $sql  .= $virgula." k111_creditoinicial = $this->k111_creditoinicial ";
       $virgula = ",";
       if(trim($this->k111_creditoinicial) == null ){ 
         $this->erro_sql = " Campo Crédito Inicial da Conta nao Informado.";
         $this->erro_campo = "k111_creditoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_debitoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_debitoinicial"])){ 
       $sql  .= $virgula." k111_debitoinicial = $this->k111_debitoinicial ";
       $virgula = ",";
       if(trim($this->k111_debitoinicial) == null ){ 
         $this->erro_sql = " Campo Débito Inicial nao Informado.";
         $this->erro_campo = "k111_debitoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_anousu"])){ 
       $sql  .= $virgula." k111_anousu = $this->k111_anousu ";
       $virgula = ",";
       if(trim($this->k111_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "k111_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_dataatualizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_dia"] !="") ){ 
       $sql  .= $virgula." k111_dataatualizacao = '$this->k111_dataatualizacao' ";
       $virgula = ",";
       if(trim($this->k111_dataatualizacao) == null ){ 
         $this->erro_sql = " Campo Data da Atualização do Saldo nao Informado.";
         $this->erro_campo = "k111_dataatualizacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao_dia"])){ 
         $sql  .= $virgula." k111_dataatualizacao = null ";
         $virgula = ",";
         if(trim($this->k111_dataatualizacao) == null ){ 
           $this->erro_sql = " Campo Data da Atualização do Saldo nao Informado.";
           $this->erro_campo = "k111_dataatualizacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k111_creditoatualizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_creditoatualizado"])){ 
       $sql  .= $virgula." k111_creditoatualizado = $this->k111_creditoatualizado ";
       $virgula = ",";
       if(trim($this->k111_creditoatualizado) == null ){ 
         $this->erro_sql = " Campo Crédito Atualizado nao Informado.";
         $this->erro_campo = "k111_creditoatualizado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k111_debitoatualizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k111_debitoatualizado"])){ 
       $sql  .= $virgula." k111_debitoatualizado = $this->k111_debitoatualizado ";
       $virgula = ",";
       if(trim($this->k111_debitoatualizado) == null ){ 
         $this->erro_sql = " Campo Débito Atualizado nao Informado.";
         $this->erro_campo = "k111_debitoatualizado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k111_sequencial!=null){
       $sql .= " k111_sequencial = $this->k111_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k111_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14522,'$this->k111_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_sequencial"]) || $this->k111_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2559,14522,'".AddSlashes(pg_result($resaco,$conresaco,'k111_sequencial'))."','$this->k111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_tabplan"]) || $this->k111_tabplan != "")
           $resac = db_query("insert into db_acount values($acount,2559,14523,'".AddSlashes(pg_result($resaco,$conresaco,'k111_tabplan'))."','$this->k111_tabplan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_recurso"]) || $this->k111_recurso != "")
           $resac = db_query("insert into db_acount values($acount,2559,14524,'".AddSlashes(pg_result($resaco,$conresaco,'k111_recurso'))."','$this->k111_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_dataimplantacao"]) || $this->k111_dataimplantacao != "")
           $resac = db_query("insert into db_acount values($acount,2559,14525,'".AddSlashes(pg_result($resaco,$conresaco,'k111_dataimplantacao'))."','$this->k111_dataimplantacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_creditoinicial"]) || $this->k111_creditoinicial != "")
           $resac = db_query("insert into db_acount values($acount,2559,14526,'".AddSlashes(pg_result($resaco,$conresaco,'k111_creditoinicial'))."','$this->k111_creditoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_debitoinicial"]) || $this->k111_debitoinicial != "")
           $resac = db_query("insert into db_acount values($acount,2559,14527,'".AddSlashes(pg_result($resaco,$conresaco,'k111_debitoinicial'))."','$this->k111_debitoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_anousu"]) || $this->k111_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2559,14528,'".AddSlashes(pg_result($resaco,$conresaco,'k111_anousu'))."','$this->k111_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_dataatualizacao"]) || $this->k111_dataatualizacao != "")
           $resac = db_query("insert into db_acount values($acount,2559,14529,'".AddSlashes(pg_result($resaco,$conresaco,'k111_dataatualizacao'))."','$this->k111_dataatualizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_creditoatualizado"]) || $this->k111_creditoatualizado != "")
           $resac = db_query("insert into db_acount values($acount,2559,14531,'".AddSlashes(pg_result($resaco,$conresaco,'k111_creditoatualizado'))."','$this->k111_creditoatualizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k111_debitoatualizado"]) || $this->k111_debitoatualizado != "")
           $resac = db_query("insert into db_acount values($acount,2559,14530,'".AddSlashes(pg_result($resaco,$conresaco,'k111_debitoatualizado'))."','$this->k111_debitoatualizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos dos recursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos dos recursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k111_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k111_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14522,'$k111_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2559,14522,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14523,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_tabplan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14524,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14525,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_dataimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14526,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_creditoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14527,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_debitoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14528,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14529,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14531,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_creditoatualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2559,14530,'','".AddSlashes(pg_result($resaco,$iresaco,'k111_debitoatualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabplansaldorecurso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k111_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k111_sequencial = $k111_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos dos recursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos dos recursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k111_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabplansaldorecurso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabplansaldorecurso ";
     $sql .= "      inner join tabplan  on  tabplan.k02_codigo = tabplansaldorecurso.k111_tabplan and  tabplan.k02_anousu = tabplansaldorecurso.k111_anousu";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = tabplansaldorecurso.k111_recurso";
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_anousu = tabplan.k02_anousu and  conplanoexe.c62_reduz = tabplan.k02_reduz";
     $sql2 = "";
     if($dbwhere==""){
       if($k111_sequencial!=null ){
         $sql2 .= " where tabplansaldorecurso.k111_sequencial = $k111_sequencial "; 
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
   function sql_query_file ( $k111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabplansaldorecurso ";
     $sql2 = "";
     if($dbwhere==""){
       if($k111_sequencial!=null ){
         $sql2 .= " where tabplansaldorecurso.k111_sequencial = $k111_sequencial "; 
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