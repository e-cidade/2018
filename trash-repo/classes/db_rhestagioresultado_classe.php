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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhestagioresultado
class cl_rhestagioresultado { 
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
   var $h65_sequencial = 0; 
   var $h65_rhestagioagenda = 0; 
   var $h65_data_dia = null; 
   var $h65_data_mes = null; 
   var $h65_data_ano = null; 
   var $h65_data = null; 
   var $h65_rhportaria = 0; 
   var $h65_resultado = null; 
   var $h65_pontos = 0; 
   var $h65_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h65_sequencial = int4 = Código do Resultado 
                 h65_rhestagioagenda = int4 = Código do Estágio 
                 h65_data = date = Data 
                 h65_rhportaria = int4 = Código da Portaria 
                 h65_resultado = char(1) = Resultado 
                 h65_pontos = float4 = Pontos Obtidos 
                 h65_observacao = text = Observacoes 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioresultado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioresultado"); 
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
       $this->h65_sequencial = ($this->h65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_sequencial"]:$this->h65_sequencial);
       $this->h65_rhestagioagenda = ($this->h65_rhestagioagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_rhestagioagenda"]:$this->h65_rhestagioagenda);
       if($this->h65_data == ""){
         $this->h65_data_dia = ($this->h65_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_data_dia"]:$this->h65_data_dia);
         $this->h65_data_mes = ($this->h65_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_data_mes"]:$this->h65_data_mes);
         $this->h65_data_ano = ($this->h65_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_data_ano"]:$this->h65_data_ano);
         if($this->h65_data_dia != ""){
            $this->h65_data = $this->h65_data_ano."-".$this->h65_data_mes."-".$this->h65_data_dia;
         }
       }
       $this->h65_rhportaria = ($this->h65_rhportaria == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_rhportaria"]:$this->h65_rhportaria);
       $this->h65_resultado = ($this->h65_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_resultado"]:$this->h65_resultado);
       $this->h65_pontos = ($this->h65_pontos == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_pontos"]:$this->h65_pontos);
       $this->h65_observacao = ($this->h65_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_observacao"]:$this->h65_observacao);
     }else{
       $this->h65_sequencial = ($this->h65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h65_sequencial"]:$this->h65_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h65_sequencial){ 
      $this->atualizacampos();
     if($this->h65_rhestagioagenda == null ){ 
       $this->erro_sql = " Campo Código do Estágio nao Informado.";
       $this->erro_campo = "h65_rhestagioagenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h65_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "h65_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h65_rhportaria == null ){ 
       $this->erro_sql = " Campo Código da Portaria nao Informado.";
       $this->erro_campo = "h65_rhportaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h65_resultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "h65_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h65_pontos == null ){ 
       $this->erro_sql = " Campo Pontos Obtidos nao Informado.";
       $this->erro_campo = "h65_pontos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h65_observacao == null ){ 
       $this->erro_sql = " Campo Observacoes nao Informado.";
       $this->erro_campo = "h65_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h65_sequencial == "" || $h65_sequencial == null ){
       $result = db_query("select nextval('rhestagioresultado_h65_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioresultado_h65_sequencial_seq do campo: h65_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h65_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioresultado_h65_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h65_sequencial)){
         $this->erro_sql = " Campo h65_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h65_sequencial = $h65_sequencial; 
       }
     }
     if(($this->h65_sequencial == null) || ($this->h65_sequencial == "") ){ 
       $this->erro_sql = " Campo h65_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioresultado(
                                       h65_sequencial 
                                      ,h65_rhestagioagenda 
                                      ,h65_data 
                                      ,h65_rhportaria 
                                      ,h65_resultado 
                                      ,h65_pontos 
                                      ,h65_observacao 
                       )
                values (
                                $this->h65_sequencial 
                               ,$this->h65_rhestagioagenda 
                               ,".($this->h65_data == "null" || $this->h65_data == ""?"null":"'".$this->h65_data."'")." 
                               ,$this->h65_rhportaria 
                               ,'$this->h65_resultado' 
                               ,$this->h65_pontos 
                               ,'$this->h65_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado do estagio ($this->h65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado do estagio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado do estagio ($this->h65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h65_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h65_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10935,'$this->h65_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1888,10935,'','".AddSlashes(pg_result($resaco,0,'h65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10936,'','".AddSlashes(pg_result($resaco,0,'h65_rhestagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10940,'','".AddSlashes(pg_result($resaco,0,'h65_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10938,'','".AddSlashes(pg_result($resaco,0,'h65_rhportaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10937,'','".AddSlashes(pg_result($resaco,0,'h65_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10939,'','".AddSlashes(pg_result($resaco,0,'h65_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1888,10941,'','".AddSlashes(pg_result($resaco,0,'h65_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h65_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioresultado set ";
     $virgula = "";
     if(trim($this->h65_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_sequencial"])){ 
       $sql  .= $virgula." h65_sequencial = $this->h65_sequencial ";
       $virgula = ",";
       if(trim($this->h65_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Resultado nao Informado.";
         $this->erro_campo = "h65_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h65_rhestagioagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_rhestagioagenda"])){ 
       $sql  .= $virgula." h65_rhestagioagenda = $this->h65_rhestagioagenda ";
       $virgula = ",";
       if(trim($this->h65_rhestagioagenda) == null ){ 
         $this->erro_sql = " Campo Código do Estágio nao Informado.";
         $this->erro_campo = "h65_rhestagioagenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h65_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h65_data_dia"] !="") ){ 
       $sql  .= $virgula." h65_data = '$this->h65_data' ";
       $virgula = ",";
       if(trim($this->h65_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "h65_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h65_data_dia"])){ 
         $sql  .= $virgula." h65_data = null ";
         $virgula = ",";
         if(trim($this->h65_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "h65_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h65_rhportaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_rhportaria"])){ 
       $sql  .= $virgula." h65_rhportaria = $this->h65_rhportaria ";
       $virgula = ",";
       if(trim($this->h65_rhportaria) == null ){ 
         $this->erro_sql = " Campo Código da Portaria nao Informado.";
         $this->erro_campo = "h65_rhportaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h65_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_resultado"])){ 
       $sql  .= $virgula." h65_resultado = '$this->h65_resultado' ";
       $virgula = ",";
       if(trim($this->h65_resultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "h65_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h65_pontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_pontos"])){ 
       $sql  .= $virgula." h65_pontos = $this->h65_pontos ";
       $virgula = ",";
       if(trim($this->h65_pontos) == null ){ 
         $this->erro_sql = " Campo Pontos Obtidos nao Informado.";
         $this->erro_campo = "h65_pontos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h65_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h65_observacao"])){ 
       $sql  .= $virgula." h65_observacao = '$this->h65_observacao' ";
       $virgula = ",";
       if(trim($this->h65_observacao) == null ){ 
         $this->erro_sql = " Campo Observacoes nao Informado.";
         $this->erro_campo = "h65_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h65_sequencial!=null){
       $sql .= " h65_sequencial = $this->h65_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h65_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10935,'$this->h65_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1888,10935,'".AddSlashes(pg_result($resaco,$conresaco,'h65_sequencial'))."','$this->h65_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_rhestagioagenda"]))
           $resac = db_query("insert into db_acount values($acount,1888,10936,'".AddSlashes(pg_result($resaco,$conresaco,'h65_rhestagioagenda'))."','$this->h65_rhestagioagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_data"]))
           $resac = db_query("insert into db_acount values($acount,1888,10940,'".AddSlashes(pg_result($resaco,$conresaco,'h65_data'))."','$this->h65_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_rhportaria"]))
           $resac = db_query("insert into db_acount values($acount,1888,10938,'".AddSlashes(pg_result($resaco,$conresaco,'h65_rhportaria'))."','$this->h65_rhportaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_resultado"]))
           $resac = db_query("insert into db_acount values($acount,1888,10937,'".AddSlashes(pg_result($resaco,$conresaco,'h65_resultado'))."','$this->h65_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_pontos"]))
           $resac = db_query("insert into db_acount values($acount,1888,10939,'".AddSlashes(pg_result($resaco,$conresaco,'h65_pontos'))."','$this->h65_pontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h65_observacao"]))
           $resac = db_query("insert into db_acount values($acount,1888,10941,'".AddSlashes(pg_result($resaco,$conresaco,'h65_observacao'))."','$this->h65_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do estagio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do estagio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h65_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h65_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10935,'$h65_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1888,10935,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10936,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_rhestagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10940,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10938,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_rhportaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10937,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10939,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1888,10941,'','".AddSlashes(pg_result($resaco,$iresaco,'h65_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioresultado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h65_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h65_sequencial = $h65_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do estagio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do estagio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h65_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioresultado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioresultado ";
     $sql .= "      inner join portaria  on  portaria.h31_sequencial = rhestagioresultado.h65_rhportaria";
     $sql .= "      inner join rhestagioagenda  on  rhestagioagenda.h57_sequencial = rhestagioresultado.h65_rhestagioagenda";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = portaria.h31_usuario";
     $sql .= "      inner join portariatipo  on  portariatipo.h30_sequencial = portaria.h31_portariatipo";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhestagioagenda.h57_regist";
     $sql .= "      inner join rhestagio  as a on   a.h50_sequencial = rhestagioagenda.h57_rhestagio";
     $sql2 = "";
     if($dbwhere==""){
       if($h65_sequencial!=null ){
         $sql2 .= " where rhestagioresultado.h65_sequencial = $h65_sequencial "; 
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
   function sql_query_file ( $h65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioresultado ";
     $sql2 = "";
     if($dbwhere==""){
       if($h65_sequencial!=null ){
         $sql2 .= " where rhestagioresultado.h65_sequencial = $h65_sequencial "; 
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
   function sql_query_resultado ( $h65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioagenda ";
     $sql .= "  inner join rhestagio           on h57_rhestagio       = h50_sequencial ";
     $sql .= "  inner join rhestagioperiodo    on h55_rhestagio       = h50_sequencial ";
     $sql .= "  inner join rhpessoal           on h57_regist          = rh01_regist    ";
     $sql .= "  inner join cgm                 on rh01_numcgm         = z01_numcgm     ";
     $sql .= "  inner join rhestagioagendadata on h64_estagioagenda   = h57_sequencial ";
     $sql .= "  left  join rhestagioavaliacao  on h56_rhestagioagenda = h64_sequencial ";
     $sql .= "  left  join rhestagioresultado  on h65_rhestagioagenda = h57_sequencial ";
     $sql .= "  left  join portaria            on h65_rhportaria      = h31_sequencial ";
     $sql .= "  left  join portariaassenta     on h31_sequencial      = h33_portaria   ";
     $sql .= "  left  join assenta            on h16_codigo          = h33_assenta    ";
     $sql2 = "";
     if($dbwhere==""){
       if($h65_sequencial!=null ){
         $sql2 .= " where rhestagioresultado.h65_sequencial = $h65_sequencial "; 
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