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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoitemexecutado
class cl_acordoitemexecutado { 
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
   var $ac29_sequencial = 0; 
   var $ac29_acordoitem = 0; 
   var $ac29_quantidade = 0; 
   var $ac29_valor = 0; 
   var $ac29_tipo = 0; 
   var $ac29_automatico = 'f'; 
   var $ac29_numeroprocesso = null; 
   var $ac29_notafiscal = null; 
   var $ac29_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac29_sequencial = int4 = Código Sequencial 
                 ac29_acordoitem = int4 = Item do Acordo 
                 ac29_quantidade = float8 = Quantidade 
                 ac29_valor = float8 = Valor 
                 ac29_tipo = int4 = Tipo 
                 ac29_automatico = bool = Lançamento automático 
                 ac29_numeroprocesso = varchar(60) = Número do Processo 
                 ac29_notafiscal = varchar(60) = Nota Fiscal 
                 ac29_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemexecutado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemexecutado"); 
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
       $this->ac29_sequencial = ($this->ac29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_sequencial"]:$this->ac29_sequencial);
       $this->ac29_acordoitem = ($this->ac29_acordoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_acordoitem"]:$this->ac29_acordoitem);
       $this->ac29_quantidade = ($this->ac29_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_quantidade"]:$this->ac29_quantidade);
       $this->ac29_valor = ($this->ac29_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_valor"]:$this->ac29_valor);
       $this->ac29_tipo = ($this->ac29_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_tipo"]:$this->ac29_tipo);
       $this->ac29_automatico = ($this->ac29_automatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ac29_automatico"]:$this->ac29_automatico);
       $this->ac29_numeroprocesso = ($this->ac29_numeroprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_numeroprocesso"]:$this->ac29_numeroprocesso);
       $this->ac29_notafiscal = ($this->ac29_notafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_notafiscal"]:$this->ac29_notafiscal);
       $this->ac29_observacao = ($this->ac29_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_observacao"]:$this->ac29_observacao);
     }else{
       $this->ac29_sequencial = ($this->ac29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac29_sequencial"]:$this->ac29_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac29_sequencial){ 
      $this->atualizacampos();
     if($this->ac29_acordoitem == null ){ 
       $this->erro_sql = " Campo Item do Acordo nao Informado.";
       $this->erro_campo = "ac29_acordoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac29_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "ac29_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac29_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "ac29_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac29_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "ac29_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac29_automatico == null ){ 
       $this->erro_sql = " Campo Lançamento automático nao Informado.";
       $this->erro_campo = "ac29_automatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac29_sequencial == "" || $ac29_sequencial == null ){
       $result = db_query("select nextval('acordoitemexecutado_ac29_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemexecutado_ac29_sequencial_seq do campo: ac29_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac29_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemexecutado_ac29_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac29_sequencial)){
         $this->erro_sql = " Campo ac29_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac29_sequencial = $ac29_sequencial; 
       }
     }
     if(($this->ac29_sequencial == null) || ($this->ac29_sequencial == "") ){ 
       $this->erro_sql = " Campo ac29_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemexecutado(
                                       ac29_sequencial 
                                      ,ac29_acordoitem 
                                      ,ac29_quantidade 
                                      ,ac29_valor 
                                      ,ac29_tipo 
                                      ,ac29_automatico 
                                      ,ac29_numeroprocesso 
                                      ,ac29_notafiscal 
                                      ,ac29_observacao 
                       )
                values (
                                $this->ac29_sequencial 
                               ,$this->ac29_acordoitem 
                               ,$this->ac29_quantidade 
                               ,$this->ac29_valor 
                               ,$this->ac29_tipo 
                               ,'$this->ac29_automatico' 
                               ,'$this->ac29_numeroprocesso' 
                               ,'$this->ac29_notafiscal' 
                               ,'$this->ac29_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Execuçoes dos contratos ($this->ac29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Execuçoes dos contratos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Execuçoes dos contratos ($this->ac29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac29_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac29_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16724,'$this->ac29_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2942,16724,'','".AddSlashes(pg_result($resaco,0,'ac29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,16725,'','".AddSlashes(pg_result($resaco,0,'ac29_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,16726,'','".AddSlashes(pg_result($resaco,0,'ac29_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,16727,'','".AddSlashes(pg_result($resaco,0,'ac29_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,16728,'','".AddSlashes(pg_result($resaco,0,'ac29_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,16729,'','".AddSlashes(pg_result($resaco,0,'ac29_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,18484,'','".AddSlashes(pg_result($resaco,0,'ac29_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,18483,'','".AddSlashes(pg_result($resaco,0,'ac29_notafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2942,18485,'','".AddSlashes(pg_result($resaco,0,'ac29_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac29_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemexecutado set ";
     $virgula = "";
     if(trim($this->ac29_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_sequencial"])){ 
       $sql  .= $virgula." ac29_sequencial = $this->ac29_sequencial ";
       $virgula = ",";
       if(trim($this->ac29_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ac29_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_acordoitem"])){ 
       $sql  .= $virgula." ac29_acordoitem = $this->ac29_acordoitem ";
       $virgula = ",";
       if(trim($this->ac29_acordoitem) == null ){ 
         $this->erro_sql = " Campo Item do Acordo nao Informado.";
         $this->erro_campo = "ac29_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_quantidade"])){ 
       $sql  .= $virgula." ac29_quantidade = $this->ac29_quantidade ";
       $virgula = ",";
       if(trim($this->ac29_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "ac29_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_valor"])){ 
       $sql  .= $virgula." ac29_valor = $this->ac29_valor ";
       $virgula = ",";
       if(trim($this->ac29_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "ac29_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_tipo"])){ 
       $sql  .= $virgula." ac29_tipo = $this->ac29_tipo ";
       $virgula = ",";
       if(trim($this->ac29_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "ac29_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_automatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_automatico"])){ 
       $sql  .= $virgula." ac29_automatico = '$this->ac29_automatico' ";
       $virgula = ",";
       if(trim($this->ac29_automatico) == null ){ 
         $this->erro_sql = " Campo Lançamento automático nao Informado.";
         $this->erro_campo = "ac29_automatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac29_numeroprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_numeroprocesso"])){ 
       $sql  .= $virgula." ac29_numeroprocesso = '$this->ac29_numeroprocesso' ";
       $virgula = ",";
     }
     if(trim($this->ac29_notafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_notafiscal"])){ 
       $sql  .= $virgula." ac29_notafiscal = '$this->ac29_notafiscal' ";
       $virgula = ",";
     }
     if(trim($this->ac29_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac29_observacao"])){ 
       $sql  .= $virgula." ac29_observacao = '$this->ac29_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac29_sequencial!=null){
       $sql .= " ac29_sequencial = $this->ac29_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac29_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16724,'$this->ac29_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_sequencial"]) || $this->ac29_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2942,16724,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_sequencial'))."','$this->ac29_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_acordoitem"]) || $this->ac29_acordoitem != "")
           $resac = db_query("insert into db_acount values($acount,2942,16725,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_acordoitem'))."','$this->ac29_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_quantidade"]) || $this->ac29_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2942,16726,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_quantidade'))."','$this->ac29_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_valor"]) || $this->ac29_valor != "")
           $resac = db_query("insert into db_acount values($acount,2942,16727,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_valor'))."','$this->ac29_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_tipo"]) || $this->ac29_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2942,16728,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_tipo'))."','$this->ac29_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_automatico"]) || $this->ac29_automatico != "")
           $resac = db_query("insert into db_acount values($acount,2942,16729,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_automatico'))."','$this->ac29_automatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_numeroprocesso"]) || $this->ac29_numeroprocesso != "")
           $resac = db_query("insert into db_acount values($acount,2942,18484,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_numeroprocesso'))."','$this->ac29_numeroprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_notafiscal"]) || $this->ac29_notafiscal != "")
           $resac = db_query("insert into db_acount values($acount,2942,18483,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_notafiscal'))."','$this->ac29_notafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac29_observacao"]) || $this->ac29_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2942,18485,'".AddSlashes(pg_result($resaco,$conresaco,'ac29_observacao'))."','$this->ac29_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Execuçoes dos contratos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Execuçoes dos contratos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac29_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac29_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16724,'$ac29_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2942,16724,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,16725,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,16726,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,16727,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,16728,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,16729,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,18484,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,18483,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_notafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2942,18485,'','".AddSlashes(pg_result($resaco,$iresaco,'ac29_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemexecutado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac29_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac29_sequencial = $ac29_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Execuçoes dos contratos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Execuçoes dos contratos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac29_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemexecutado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutado ";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemexecutado.ac29_acordoitem";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac29_sequencial!=null ){
         $sql2 .= " where acordoitemexecutado.ac29_sequencial = $ac29_sequencial "; 
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
   function sql_query_file ( $ac29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutado ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac29_sequencial!=null ){
         $sql2 .= " where acordoitemexecutado.ac29_sequencial = $ac29_sequencial "; 
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