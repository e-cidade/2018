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

//MODULO: empenho
//CLASSE DA ENTIDADE retencaoreceitas
class cl_retencaoreceitas { 
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
   var $e23_sequencial = 0; 
   var $e23_retencaotiporec = 0; 
   var $e23_retencaopagordem = 0; 
   var $e23_dtcalculo_dia = null; 
   var $e23_dtcalculo_mes = null; 
   var $e23_dtcalculo_ano = null; 
   var $e23_dtcalculo = null; 
   var $e23_valor = 0; 
   var $e23_deducao = 0; 
   var $e23_valorbase = 0; 
   var $e23_aliquota = 0; 
   var $e23_valorretencao = 0; 
   var $e23_ativo = 'f'; 
   var $e23_recolhido = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e23_sequencial = int4 = Código Sequencial 
                 e23_retencaotiporec = int4 = Retenção 
                 e23_retencaopagordem = int4 = Código da Retenção 
                 e23_dtcalculo = date = Data do Cálculo 
                 e23_valor = float8 = Valor da nota 
                 e23_deducao = float8 = Valor da Dedução 
                 e23_valorbase = float8 = Valor da Base de Cálculo 
                 e23_aliquota = float8 = Valor da Aliquota 
                 e23_valorretencao = float8 = Valor final da Retenção 
                 e23_ativo = bool = Registro ativo 
                 e23_recolhido = bool = Recolhido 
                 ";
   //funcao construtor da classe 
   function cl_retencaoreceitas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retencaoreceitas"); 
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
       $this->e23_sequencial = ($this->e23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_sequencial"]:$this->e23_sequencial);
       $this->e23_retencaotiporec = ($this->e23_retencaotiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_retencaotiporec"]:$this->e23_retencaotiporec);
       $this->e23_retencaopagordem = ($this->e23_retencaopagordem == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_retencaopagordem"]:$this->e23_retencaopagordem);
       if($this->e23_dtcalculo == ""){
         $this->e23_dtcalculo_dia = ($this->e23_dtcalculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_dia"]:$this->e23_dtcalculo_dia);
         $this->e23_dtcalculo_mes = ($this->e23_dtcalculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_mes"]:$this->e23_dtcalculo_mes);
         $this->e23_dtcalculo_ano = ($this->e23_dtcalculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_ano"]:$this->e23_dtcalculo_ano);
         if($this->e23_dtcalculo_dia != ""){
            $this->e23_dtcalculo = $this->e23_dtcalculo_ano."-".$this->e23_dtcalculo_mes."-".$this->e23_dtcalculo_dia;
         }
       }
       $this->e23_valor = ($this->e23_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_valor"]:$this->e23_valor);
       $this->e23_deducao = ($this->e23_deducao == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_deducao"]:$this->e23_deducao);
       $this->e23_valorbase = ($this->e23_valorbase == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_valorbase"]:$this->e23_valorbase);
       $this->e23_aliquota = ($this->e23_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_aliquota"]:$this->e23_aliquota);
       $this->e23_valorretencao = ($this->e23_valorretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_valorretencao"]:$this->e23_valorretencao);
       $this->e23_ativo = ($this->e23_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["e23_ativo"]:$this->e23_ativo);
       $this->e23_recolhido = ($this->e23_recolhido == "f"?@$GLOBALS["HTTP_POST_VARS"]["e23_recolhido"]:$this->e23_recolhido);
     }else{
       $this->e23_sequencial = ($this->e23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e23_sequencial"]:$this->e23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e23_sequencial){ 
      $this->atualizacampos();
     if($this->e23_retencaotiporec == null ){ 
       $this->erro_sql = " Campo Retenção nao Informado.";
       $this->erro_campo = "e23_retencaotiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_retencaopagordem == null ){ 
       $this->erro_sql = " Campo Código da Retenção nao Informado.";
       $this->erro_campo = "e23_retencaopagordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_dtcalculo == null ){ 
       $this->erro_sql = " Campo Data do Cálculo nao Informado.";
       $this->erro_campo = "e23_dtcalculo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_valor == null ){ 
       $this->erro_sql = " Campo Valor da nota nao Informado.";
       $this->erro_campo = "e23_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_deducao == null ){ 
       $this->erro_sql = " Campo Valor da Dedução nao Informado.";
       $this->erro_campo = "e23_deducao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_valorbase == null ){ 
       $this->erro_sql = " Campo Valor da Base de Cálculo nao Informado.";
       $this->erro_campo = "e23_valorbase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_aliquota == null ){ 
       $this->erro_sql = " Campo Valor da Aliquota nao Informado.";
       $this->erro_campo = "e23_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_valorretencao == null ){ 
       $this->erro_sql = " Campo Valor final da Retenção nao Informado.";
       $this->erro_campo = "e23_valorretencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_ativo == null ){ 
       $this->erro_sql = " Campo Registro ativo nao Informado.";
       $this->erro_campo = "e23_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e23_recolhido == null ){ 
       $this->erro_sql = " Campo Recolhido nao Informado.";
       $this->erro_campo = "e23_recolhido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e23_sequencial == "" || $e23_sequencial == null ){
       $result = db_query("select nextval('retencaoreceitas_e23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retencaoreceitas_e23_sequencial_seq do campo: e23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from retencaoreceitas_e23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e23_sequencial)){
         $this->erro_sql = " Campo e23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e23_sequencial = $e23_sequencial; 
       }
     }
     if(($this->e23_sequencial == null) || ($this->e23_sequencial == "") ){ 
       $this->erro_sql = " Campo e23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retencaoreceitas(
                                       e23_sequencial 
                                      ,e23_retencaotiporec 
                                      ,e23_retencaopagordem 
                                      ,e23_dtcalculo 
                                      ,e23_valor 
                                      ,e23_deducao 
                                      ,e23_valorbase 
                                      ,e23_aliquota 
                                      ,e23_valorretencao 
                                      ,e23_ativo 
                                      ,e23_recolhido 
                       )
                values (
                                $this->e23_sequencial 
                               ,$this->e23_retencaotiporec 
                               ,$this->e23_retencaopagordem 
                               ,".($this->e23_dtcalculo == "null" || $this->e23_dtcalculo == ""?"null":"'".$this->e23_dtcalculo."'")." 
                               ,$this->e23_valor 
                               ,$this->e23_deducao 
                               ,$this->e23_valorbase 
                               ,$this->e23_aliquota 
                               ,$this->e23_valorretencao 
                               ,'$this->e23_ativo' 
                               ,'$this->e23_recolhido' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "receitas da Retenção ($this->e23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "receitas da Retenção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "receitas da Retenção ($this->e23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12176,'$this->e23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2116,12176,'','".AddSlashes(pg_result($resaco,0,'e23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12177,'','".AddSlashes(pg_result($resaco,0,'e23_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12178,'','".AddSlashes(pg_result($resaco,0,'e23_retencaopagordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12185,'','".AddSlashes(pg_result($resaco,0,'e23_dtcalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12179,'','".AddSlashes(pg_result($resaco,0,'e23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12180,'','".AddSlashes(pg_result($resaco,0,'e23_deducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12181,'','".AddSlashes(pg_result($resaco,0,'e23_valorbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12182,'','".AddSlashes(pg_result($resaco,0,'e23_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12183,'','".AddSlashes(pg_result($resaco,0,'e23_valorretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12184,'','".AddSlashes(pg_result($resaco,0,'e23_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2116,12200,'','".AddSlashes(pg_result($resaco,0,'e23_recolhido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update retencaoreceitas set ";
     $virgula = "";
     if(trim($this->e23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_sequencial"])){ 
       $sql  .= $virgula." e23_sequencial = $this->e23_sequencial ";
       $virgula = ",";
       if(trim($this->e23_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_retencaotiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_retencaotiporec"])){ 
       $sql  .= $virgula." e23_retencaotiporec = $this->e23_retencaotiporec ";
       $virgula = ",";
       if(trim($this->e23_retencaotiporec) == null ){ 
         $this->erro_sql = " Campo Retenção nao Informado.";
         $this->erro_campo = "e23_retencaotiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_retencaopagordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_retencaopagordem"])){ 
       $sql  .= $virgula." e23_retencaopagordem = $this->e23_retencaopagordem ";
       $virgula = ",";
       if(trim($this->e23_retencaopagordem) == null ){ 
         $this->erro_sql = " Campo Código da Retenção nao Informado.";
         $this->erro_campo = "e23_retencaopagordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_dtcalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_dia"] !="") ){ 
       $sql  .= $virgula." e23_dtcalculo = '$this->e23_dtcalculo' ";
       $virgula = ",";
       if(trim($this->e23_dtcalculo) == null ){ 
         $this->erro_sql = " Campo Data do Cálculo nao Informado.";
         $this->erro_campo = "e23_dtcalculo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo_dia"])){ 
         $sql  .= $virgula." e23_dtcalculo = null ";
         $virgula = ",";
         if(trim($this->e23_dtcalculo) == null ){ 
           $this->erro_sql = " Campo Data do Cálculo nao Informado.";
           $this->erro_campo = "e23_dtcalculo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e23_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_valor"])){ 
       $sql  .= $virgula." e23_valor = $this->e23_valor ";
       $virgula = ",";
       if(trim($this->e23_valor) == null ){ 
         $this->erro_sql = " Campo Valor da nota nao Informado.";
         $this->erro_campo = "e23_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_deducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_deducao"])){ 
       $sql  .= $virgula." e23_deducao = $this->e23_deducao ";
       $virgula = ",";
       if(trim($this->e23_deducao) == null ){ 
         $this->erro_sql = " Campo Valor da Dedução nao Informado.";
         $this->erro_campo = "e23_deducao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_valorbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_valorbase"])){ 
       $sql  .= $virgula." e23_valorbase = $this->e23_valorbase ";
       $virgula = ",";
       if(trim($this->e23_valorbase) == null ){ 
         $this->erro_sql = " Campo Valor da Base de Cálculo nao Informado.";
         $this->erro_campo = "e23_valorbase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_aliquota"])){ 
       $sql  .= $virgula." e23_aliquota = $this->e23_aliquota ";
       $virgula = ",";
       if(trim($this->e23_aliquota) == null ){ 
         $this->erro_sql = " Campo Valor da Aliquota nao Informado.";
         $this->erro_campo = "e23_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_valorretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_valorretencao"])){ 
       $sql  .= $virgula." e23_valorretencao = $this->e23_valorretencao ";
       $virgula = ",";
       if(trim($this->e23_valorretencao) == null ){ 
         $this->erro_sql = " Campo Valor final da Retenção nao Informado.";
         $this->erro_campo = "e23_valorretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_ativo"])){ 
       $sql  .= $virgula." e23_ativo = '$this->e23_ativo' ";
       $virgula = ",";
       if(trim($this->e23_ativo) == null ){ 
         $this->erro_sql = " Campo Registro ativo nao Informado.";
         $this->erro_campo = "e23_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e23_recolhido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e23_recolhido"])){ 
       $sql  .= $virgula." e23_recolhido = '$this->e23_recolhido' ";
       $virgula = ",";
       if(trim($this->e23_recolhido) == null ){ 
         $this->erro_sql = " Campo Recolhido nao Informado.";
         $this->erro_campo = "e23_recolhido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e23_sequencial!=null){
       $sql .= " e23_sequencial = $this->e23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12176,'$this->e23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2116,12176,'".AddSlashes(pg_result($resaco,$conresaco,'e23_sequencial'))."','$this->e23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_retencaotiporec"]))
           $resac = db_query("insert into db_acount values($acount,2116,12177,'".AddSlashes(pg_result($resaco,$conresaco,'e23_retencaotiporec'))."','$this->e23_retencaotiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_retencaopagordem"]))
           $resac = db_query("insert into db_acount values($acount,2116,12178,'".AddSlashes(pg_result($resaco,$conresaco,'e23_retencaopagordem'))."','$this->e23_retencaopagordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_dtcalculo"]))
           $resac = db_query("insert into db_acount values($acount,2116,12185,'".AddSlashes(pg_result($resaco,$conresaco,'e23_dtcalculo'))."','$this->e23_dtcalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_valor"]))
           $resac = db_query("insert into db_acount values($acount,2116,12179,'".AddSlashes(pg_result($resaco,$conresaco,'e23_valor'))."','$this->e23_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_deducao"]))
           $resac = db_query("insert into db_acount values($acount,2116,12180,'".AddSlashes(pg_result($resaco,$conresaco,'e23_deducao'))."','$this->e23_deducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_valorbase"]))
           $resac = db_query("insert into db_acount values($acount,2116,12181,'".AddSlashes(pg_result($resaco,$conresaco,'e23_valorbase'))."','$this->e23_valorbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_aliquota"]))
           $resac = db_query("insert into db_acount values($acount,2116,12182,'".AddSlashes(pg_result($resaco,$conresaco,'e23_aliquota'))."','$this->e23_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_valorretencao"]))
           $resac = db_query("insert into db_acount values($acount,2116,12183,'".AddSlashes(pg_result($resaco,$conresaco,'e23_valorretencao'))."','$this->e23_valorretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_ativo"]))
           $resac = db_query("insert into db_acount values($acount,2116,12184,'".AddSlashes(pg_result($resaco,$conresaco,'e23_ativo'))."','$this->e23_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e23_recolhido"]))
           $resac = db_query("insert into db_acount values($acount,2116,12200,'".AddSlashes(pg_result($resaco,$conresaco,'e23_recolhido'))."','$this->e23_recolhido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "receitas da Retenção nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "receitas da Retenção nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12176,'$e23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2116,12176,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12177,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12178,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_retencaopagordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12185,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_dtcalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12179,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12180,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_deducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12181,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_valorbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12182,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12183,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_valorretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12184,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2116,12200,'','".AddSlashes(pg_result($resaco,$iresaco,'e23_recolhido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retencaoreceitas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e23_sequencial = $e23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "receitas da Retenção nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "receitas da Retenção nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:retencaoreceitas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaoreceitas ";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = retencaopagordem.e20_pagordem";
     $sql2 = "";
     if($dbwhere==""){
       if($e23_sequencial!=null ){
         $sql2 .= " where retencaoreceitas.e23_sequencial = $e23_sequencial "; 
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
   function sql_query_file ( $e23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaoreceitas ";
     $sql2 = "";
     if($dbwhere==""){
       if($e23_sequencial!=null ){
         $sql2 .= " where retencaoreceitas.e23_sequencial = $e23_sequencial "; 
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
   * Consulta com left para empnota
   * 
   *
   * @param integer $e23_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
   function sql_query_notas( $e23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaoreceitas ";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = retencaopagordem.e20_pagordem";
     $sql .= "      inner join pagordemnota  on  pagordem.e50_codord = pagordemnota.e71_codord";
     $sql .= "      inner join empnota            on  pagordemnota.e71_codnota = empnota.e69_codnota";
     $sql .= "      inner join retencaoempagemov  on  e23_sequencial = e27_retencaoreceitas";
     $sql .= "      left  join empagemovslips     on  e27_empagemov   = k107_empagemov";
     $sql .= "      left  join slipempagemovslips on  k107_sequencial = k108_empagemovslips";
     $sql2 = "";
     if($dbwhere==""){
       if($e23_sequencial!=null ){
         $sql2 .= " where retencaoreceitas.e23_sequencial = $e23_sequencial "; 
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

  function sql_query_consulta($e23_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
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
     $sql .= " from retencaoreceitas ";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = retencaopagordem.e20_pagordem";
     $sql .= "      inner join pagordemnota  on  pagordem.e50_codord = pagordemnota.e71_codord";
     $sql .= "      inner join empnota            on  pagordemnota.e71_codnota = empnota.e69_codnota";
     $sql .= "      inner join retencaoempagemov  on  e23_sequencial = e27_retencaoreceitas";
     $sql .= "      left  join empagemovslips     on  e27_empagemov   = k107_empagemov";
     $sql .= "      left  join slipempagemovslips on  k107_sequencial = k108_empagemovslips";
     $sql .= "      left  join retencaocorgrupocorrente on e23_sequencial = e47_retencaoreceita";
     $sql .= "      left  join corgrupocorrente on e47_corgrupocorrente = k105_sequencial";
     $sql .= "      left  join cornump as numpre on numpre.k12_data     = k105_data ";
     $sql .= "                                  and numpre.k12_autent   = k105_autent";
     $sql .= "                                  and numpre.k12_id       = k105_id";
     $sql .= "      left  join issplannumpre on numpre.k12_numpre       = q32_numpre";
     $sql2 = "";
     if($dbwhere==""){
       if($e23_sequencial!=null ){
         $sql2 .= " where retencaoreceitas.e23_sequencial = $e23_sequencial "; 
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