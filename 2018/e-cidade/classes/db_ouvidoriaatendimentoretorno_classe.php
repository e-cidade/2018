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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaatendimentoretorno
class cl_ouvidoriaatendimentoretorno { 
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
   var $ov20_sequencial = 0; 
   var $ov20_tiporetorno = 0; 
   var $ov20_ouvidoriaatendimento = 0; 
   var $ov20_dataretorno_dia = null; 
   var $ov20_dataretorno_mes = null; 
   var $ov20_dataretorno_ano = null; 
   var $ov20_dataretorno = null; 
   var $ov20_horaretorno = null; 
   var $ov20_informa = null; 
   var $ov20_resposta = null; 
   var $ov20_confirma = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov20_sequencial = int4 = Sequencial 
                 ov20_tiporetorno = int4 = Tipo de Retorno 
                 ov20_ouvidoriaatendimento = int4 = Atendimento 
                 ov20_dataretorno = date = Data Retorno 
                 ov20_horaretorno = char(5) = Hora Retorno 
                 ov20_informa = text = Informação 
                 ov20_resposta = text = Resposta 
                 ov20_confirma = bool = Confirmação 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaatendimentoretorno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaatendimentoretorno"); 
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
       $this->ov20_sequencial = ($this->ov20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_sequencial"]:$this->ov20_sequencial);
       $this->ov20_tiporetorno = ($this->ov20_tiporetorno == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_tiporetorno"]:$this->ov20_tiporetorno);
       $this->ov20_ouvidoriaatendimento = ($this->ov20_ouvidoriaatendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_ouvidoriaatendimento"]:$this->ov20_ouvidoriaatendimento);
       if($this->ov20_dataretorno == ""){
         $this->ov20_dataretorno_dia = ($this->ov20_dataretorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_dia"]:$this->ov20_dataretorno_dia);
         $this->ov20_dataretorno_mes = ($this->ov20_dataretorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_mes"]:$this->ov20_dataretorno_mes);
         $this->ov20_dataretorno_ano = ($this->ov20_dataretorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_ano"]:$this->ov20_dataretorno_ano);
         if($this->ov20_dataretorno_dia != ""){
            $this->ov20_dataretorno = $this->ov20_dataretorno_ano."-".$this->ov20_dataretorno_mes."-".$this->ov20_dataretorno_dia;
         }
       }
       $this->ov20_horaretorno = ($this->ov20_horaretorno == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_horaretorno"]:$this->ov20_horaretorno);
       $this->ov20_informa = ($this->ov20_informa == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_informa"]:$this->ov20_informa);
       $this->ov20_resposta = ($this->ov20_resposta == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_resposta"]:$this->ov20_resposta);
       $this->ov20_confirma = ($this->ov20_confirma == "f"?@$GLOBALS["HTTP_POST_VARS"]["ov20_confirma"]:$this->ov20_confirma);
     }else{
       $this->ov20_sequencial = ($this->ov20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov20_sequencial"]:$this->ov20_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov20_sequencial){ 
      $this->atualizacampos();
     if($this->ov20_tiporetorno == null ){ 
       $this->erro_sql = " Campo Tipo de Retorno nao Informado.";
       $this->erro_campo = "ov20_tiporetorno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov20_ouvidoriaatendimento == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "ov20_ouvidoriaatendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov20_dataretorno == null ){ 
       $this->erro_sql = " Campo Data Retorno nao Informado.";
       $this->erro_campo = "ov20_dataretorno_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov20_horaretorno == null ){ 
       $this->erro_sql = " Campo Hora Retorno nao Informado.";
       $this->erro_campo = "ov20_horaretorno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov20_informa == null ){ 
       $this->erro_sql = " Campo Informação nao Informado.";
       $this->erro_campo = "ov20_informa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov20_confirma == null ){ 
       $this->erro_sql = " Campo Confirmação nao Informado.";
       $this->erro_campo = "ov20_confirma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov20_sequencial == "" || $ov20_sequencial == null ){
       $result = db_query("select nextval('ouvidoriaatendimentoretorno_ov20_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriaatendimentoretorno_ov20_sequencial_seq do campo: ov20_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov20_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriaatendimentoretorno_ov20_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov20_sequencial)){
         $this->erro_sql = " Campo ov20_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov20_sequencial = $ov20_sequencial; 
       }
     }
     if(($this->ov20_sequencial == null) || ($this->ov20_sequencial == "") ){ 
       $this->erro_sql = " Campo ov20_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaatendimentoretorno(
                                       ov20_sequencial 
                                      ,ov20_tiporetorno 
                                      ,ov20_ouvidoriaatendimento 
                                      ,ov20_dataretorno 
                                      ,ov20_horaretorno 
                                      ,ov20_informa 
                                      ,ov20_resposta 
                                      ,ov20_confirma 
                       )
                values (
                                $this->ov20_sequencial 
                               ,$this->ov20_tiporetorno 
                               ,$this->ov20_ouvidoriaatendimento 
                               ,".($this->ov20_dataretorno == "null" || $this->ov20_dataretorno == ""?"null":"'".$this->ov20_dataretorno."'")." 
                               ,'$this->ov20_horaretorno' 
                               ,'$this->ov20_informa' 
                               ,'$this->ov20_resposta' 
                               ,'$this->ov20_confirma' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retorno do Atendimento de Ouvidoria ($this->ov20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retorno do Atendimento de Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retorno do Atendimento de Ouvidoria ($this->ov20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov20_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov20_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14899,'$this->ov20_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2623,14899,'','".AddSlashes(pg_result($resaco,0,'ov20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14900,'','".AddSlashes(pg_result($resaco,0,'ov20_tiporetorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14901,'','".AddSlashes(pg_result($resaco,0,'ov20_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14902,'','".AddSlashes(pg_result($resaco,0,'ov20_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14903,'','".AddSlashes(pg_result($resaco,0,'ov20_horaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14904,'','".AddSlashes(pg_result($resaco,0,'ov20_informa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14905,'','".AddSlashes(pg_result($resaco,0,'ov20_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2623,14906,'','".AddSlashes(pg_result($resaco,0,'ov20_confirma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov20_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaatendimentoretorno set ";
     $virgula = "";
     if(trim($this->ov20_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_sequencial"])){ 
       $sql  .= $virgula." ov20_sequencial = $this->ov20_sequencial ";
       $virgula = ",";
       if(trim($this->ov20_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov20_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov20_tiporetorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_tiporetorno"])){ 
       $sql  .= $virgula." ov20_tiporetorno = $this->ov20_tiporetorno ";
       $virgula = ",";
       if(trim($this->ov20_tiporetorno) == null ){ 
         $this->erro_sql = " Campo Tipo de Retorno nao Informado.";
         $this->erro_campo = "ov20_tiporetorno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov20_ouvidoriaatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_ouvidoriaatendimento"])){ 
       $sql  .= $virgula." ov20_ouvidoriaatendimento = $this->ov20_ouvidoriaatendimento ";
       $virgula = ",";
       if(trim($this->ov20_ouvidoriaatendimento) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "ov20_ouvidoriaatendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov20_dataretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_dia"] !="") ){ 
       $sql  .= $virgula." ov20_dataretorno = '$this->ov20_dataretorno' ";
       $virgula = ",";
       if(trim($this->ov20_dataretorno) == null ){ 
         $this->erro_sql = " Campo Data Retorno nao Informado.";
         $this->erro_campo = "ov20_dataretorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno_dia"])){ 
         $sql  .= $virgula." ov20_dataretorno = null ";
         $virgula = ",";
         if(trim($this->ov20_dataretorno) == null ){ 
           $this->erro_sql = " Campo Data Retorno nao Informado.";
           $this->erro_campo = "ov20_dataretorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ov20_horaretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_horaretorno"])){ 
       $sql  .= $virgula." ov20_horaretorno = '$this->ov20_horaretorno' ";
       $virgula = ",";
       if(trim($this->ov20_horaretorno) == null ){ 
         $this->erro_sql = " Campo Hora Retorno nao Informado.";
         $this->erro_campo = "ov20_horaretorno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov20_informa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_informa"])){ 
       $sql  .= $virgula." ov20_informa = '$this->ov20_informa' ";
       $virgula = ",";
       if(trim($this->ov20_informa) == null ){ 
         $this->erro_sql = " Campo Informação nao Informado.";
         $this->erro_campo = "ov20_informa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov20_resposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_resposta"])){ 
       $sql  .= $virgula." ov20_resposta = '$this->ov20_resposta' ";
       $virgula = ",";
     }
     if(trim($this->ov20_confirma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov20_confirma"])){ 
       $sql  .= $virgula." ov20_confirma = '$this->ov20_confirma' ";
       $virgula = ",";
       if(trim($this->ov20_confirma) == null ){ 
         $this->erro_sql = " Campo Confirmação nao Informado.";
         $this->erro_campo = "ov20_confirma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov20_sequencial!=null){
       $sql .= " ov20_sequencial = $this->ov20_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov20_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14899,'$this->ov20_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_sequencial"]) || $this->ov20_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2623,14899,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_sequencial'))."','$this->ov20_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_tiporetorno"]) || $this->ov20_tiporetorno != "")
           $resac = db_query("insert into db_acount values($acount,2623,14900,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_tiporetorno'))."','$this->ov20_tiporetorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_ouvidoriaatendimento"]) || $this->ov20_ouvidoriaatendimento != "")
           $resac = db_query("insert into db_acount values($acount,2623,14901,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_ouvidoriaatendimento'))."','$this->ov20_ouvidoriaatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_dataretorno"]) || $this->ov20_dataretorno != "")
           $resac = db_query("insert into db_acount values($acount,2623,14902,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_dataretorno'))."','$this->ov20_dataretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_horaretorno"]) || $this->ov20_horaretorno != "")
           $resac = db_query("insert into db_acount values($acount,2623,14903,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_horaretorno'))."','$this->ov20_horaretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_informa"]) || $this->ov20_informa != "")
           $resac = db_query("insert into db_acount values($acount,2623,14904,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_informa'))."','$this->ov20_informa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_resposta"]) || $this->ov20_resposta != "")
           $resac = db_query("insert into db_acount values($acount,2623,14905,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_resposta'))."','$this->ov20_resposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov20_confirma"]) || $this->ov20_confirma != "")
           $resac = db_query("insert into db_acount values($acount,2623,14906,'".AddSlashes(pg_result($resaco,$conresaco,'ov20_confirma'))."','$this->ov20_confirma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retorno do Atendimento de Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retorno do Atendimento de Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov20_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov20_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14899,'$ov20_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2623,14899,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14900,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_tiporetorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14901,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14902,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14903,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_horaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14904,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_informa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14905,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2623,14906,'','".AddSlashes(pg_result($resaco,$iresaco,'ov20_confirma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaatendimentoretorno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov20_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov20_sequencial = $ov20_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retorno do Atendimento de Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retorno do Atendimento de Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov20_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaatendimentoretorno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretorno ";
     $sql .= "      inner join tiporetorno  on  tiporetorno.ov22_sequencial = ouvidoriaatendimentoretorno.ov20_tiporetorno";
     $sql .= "      inner join ouvidoriaatendimento  on  ouvidoriaatendimento.ov01_sequencial = ouvidoriaatendimentoretorno.ov20_ouvidoriaatendimento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ouvidoriaatendimento.ov01_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = ouvidoriaatendimento.ov01_depart";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = ouvidoriaatendimento.ov01_tipoprocesso";
     $sql .= "      inner join tipoidentificacao  on  tipoidentificacao.ov05_sequencial = ouvidoriaatendimento.ov01_tipoidentificacao";
     $sql .= "      inner join formareclamacao  on  formareclamacao.p42_sequencial = ouvidoriaatendimento.ov01_formareclamacao";
     $sql .= "      inner join situacaoouvidoriaatendimento  on  situacaoouvidoriaatendimento.ov18_sequencial = ouvidoriaatendimento.ov01_situacaoouvidoriaatendimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ov20_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretorno.ov20_sequencial = $ov20_sequencial "; 
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
   function sql_query_file ( $ov20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretorno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov20_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretorno.ov20_sequencial = $ov20_sequencial "; 
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