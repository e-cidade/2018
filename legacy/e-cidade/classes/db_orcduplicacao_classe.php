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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcduplicacao
class cl_orcduplicacao { 
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
   var $o75_sequencial = 0; 
   var $o75_conaberturaexe = 0; 
   var $o75_tipo = 0; 
   var $o75_previsaoinicial = 0; 
   var $o75_acrescimos = 0; 
   var $o75_reducoes = 0; 
   var $o75_atualizado = 0; 
   var $o75_percentual = 0; 
   var $o75_valorduplicar = 0; 
   var $o75_importar = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o75_sequencial = int4 = Sequencial 
                 o75_conaberturaexe = int4 = Abertura do Exercício 
                 o75_tipo = int4 = Tipo da Duplicação 
                 o75_previsaoinicial = float8 = Previsão Inicial 
                 o75_acrescimos = float8 = Acrescimos 
                 o75_reducoes = float8 = Reduções 
                 o75_atualizado = float8 = Saldo Atualizado 
                 o75_percentual = float8 = Percentual 
                 o75_valorduplicar = float8 = Valor a Duplicar 
                 o75_importar = bool = Importar 
                 ";
   //funcao construtor da classe 
   function cl_orcduplicacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcduplicacao"); 
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
       $this->o75_sequencial = ($this->o75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_sequencial"]:$this->o75_sequencial);
       $this->o75_conaberturaexe = ($this->o75_conaberturaexe == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_conaberturaexe"]:$this->o75_conaberturaexe);
       $this->o75_tipo = ($this->o75_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_tipo"]:$this->o75_tipo);
       $this->o75_previsaoinicial = ($this->o75_previsaoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_previsaoinicial"]:$this->o75_previsaoinicial);
       $this->o75_acrescimos = ($this->o75_acrescimos == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_acrescimos"]:$this->o75_acrescimos);
       $this->o75_reducoes = ($this->o75_reducoes == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_reducoes"]:$this->o75_reducoes);
       $this->o75_atualizado = ($this->o75_atualizado == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_atualizado"]:$this->o75_atualizado);
       $this->o75_percentual = ($this->o75_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_percentual"]:$this->o75_percentual);
       $this->o75_valorduplicar = ($this->o75_valorduplicar == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_valorduplicar"]:$this->o75_valorduplicar);
       $this->o75_importar = ($this->o75_importar == "f"?@$GLOBALS["HTTP_POST_VARS"]["o75_importar"]:$this->o75_importar);
     }else{
       $this->o75_sequencial = ($this->o75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o75_sequencial"]:$this->o75_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o75_sequencial){ 
      $this->atualizacampos();
     if($this->o75_conaberturaexe == null ){ 
       $this->erro_sql = " Campo Abertura do Exercício nao Informado.";
       $this->erro_campo = "o75_conaberturaexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o75_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Duplicação nao Informado.";
       $this->erro_campo = "o75_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o75_previsaoinicial == null ){ 
       $this->o75_previsaoinicial = "0";
     }
     if($this->o75_acrescimos == null ){ 
       $this->o75_acrescimos = "0";
     }
     if($this->o75_reducoes == null ){ 
       $this->o75_reducoes = "0";
     }
     if($this->o75_atualizado == null ){ 
       $this->o75_atualizado = "0";
     }
     if($this->o75_percentual == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "o75_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o75_valorduplicar == null ){ 
       $this->erro_sql = " Campo Valor a Duplicar nao Informado.";
       $this->erro_campo = "o75_valorduplicar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o75_importar == null ){ 
       $this->erro_sql = " Campo Importar nao Informado.";
       $this->erro_campo = "o75_importar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o75_sequencial == "" || $o75_sequencial == null ){
       $result = db_query("select nextval('orcduplicacao_o75_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcduplicacao_o75_sequencial_seq do campo: o75_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o75_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcduplicacao_o75_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o75_sequencial)){
         $this->erro_sql = " Campo o75_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o75_sequencial = $o75_sequencial; 
       }
     }
     if(($this->o75_sequencial == null) || ($this->o75_sequencial == "") ){ 
       $this->erro_sql = " Campo o75_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcduplicacao(
                                       o75_sequencial 
                                      ,o75_conaberturaexe 
                                      ,o75_tipo 
                                      ,o75_previsaoinicial 
                                      ,o75_acrescimos 
                                      ,o75_reducoes 
                                      ,o75_atualizado 
                                      ,o75_percentual 
                                      ,o75_valorduplicar 
                                      ,o75_importar 
                       )
                values (
                                $this->o75_sequencial 
                               ,$this->o75_conaberturaexe 
                               ,$this->o75_tipo 
                               ,$this->o75_previsaoinicial 
                               ,$this->o75_acrescimos 
                               ,$this->o75_reducoes 
                               ,$this->o75_atualizado 
                               ,$this->o75_percentual 
                               ,$this->o75_valorduplicar 
                               ,'$this->o75_importar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Duplicação do Orçamento ($this->o75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Duplicação do Orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Duplicação do Orçamento ($this->o75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o75_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o75_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10466,'$this->o75_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1809,10466,'','".AddSlashes(pg_result($resaco,0,'o75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10467,'','".AddSlashes(pg_result($resaco,0,'o75_conaberturaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10469,'','".AddSlashes(pg_result($resaco,0,'o75_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10470,'','".AddSlashes(pg_result($resaco,0,'o75_previsaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10471,'','".AddSlashes(pg_result($resaco,0,'o75_acrescimos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10472,'','".AddSlashes(pg_result($resaco,0,'o75_reducoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10473,'','".AddSlashes(pg_result($resaco,0,'o75_atualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10499,'','".AddSlashes(pg_result($resaco,0,'o75_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10474,'','".AddSlashes(pg_result($resaco,0,'o75_valorduplicar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1809,10475,'','".AddSlashes(pg_result($resaco,0,'o75_importar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o75_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcduplicacao set ";
     $virgula = "";
     if(trim($this->o75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_sequencial"])){ 
        if(trim($this->o75_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o75_sequencial"])){ 
           $this->o75_sequencial = "0" ; 
        } 
       $sql  .= $virgula." o75_sequencial = $this->o75_sequencial ";
       $virgula = ",";
     }
     if(trim($this->o75_conaberturaexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_conaberturaexe"])){ 
       $sql  .= $virgula." o75_conaberturaexe = $this->o75_conaberturaexe ";
       $virgula = ",";
       if(trim($this->o75_conaberturaexe) == null ){ 
         $this->erro_sql = " Campo Abertura do Exercício nao Informado.";
         $this->erro_campo = "o75_conaberturaexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o75_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_tipo"])){ 
       $sql  .= $virgula." o75_tipo = $this->o75_tipo ";
       $virgula = ",";
       if(trim($this->o75_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Duplicação nao Informado.";
         $this->erro_campo = "o75_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o75_previsaoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_previsaoinicial"])){ 
        if(trim($this->o75_previsaoinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o75_previsaoinicial"])){ 
           $this->o75_previsaoinicial = "0" ; 
        } 
       $sql  .= $virgula." o75_previsaoinicial = $this->o75_previsaoinicial ";
       $virgula = ",";
     }
     if(trim($this->o75_acrescimos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_acrescimos"])){ 
        if(trim($this->o75_acrescimos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o75_acrescimos"])){ 
           $this->o75_acrescimos = "0" ; 
        } 
       $sql  .= $virgula." o75_acrescimos = $this->o75_acrescimos ";
       $virgula = ",";
     }
     if(trim($this->o75_reducoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_reducoes"])){ 
        if(trim($this->o75_reducoes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o75_reducoes"])){ 
           $this->o75_reducoes = "0" ; 
        } 
       $sql  .= $virgula." o75_reducoes = $this->o75_reducoes ";
       $virgula = ",";
     }
     if(trim($this->o75_atualizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_atualizado"])){ 
        if(trim($this->o75_atualizado)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o75_atualizado"])){ 
           $this->o75_atualizado = "0" ; 
        } 
       $sql  .= $virgula." o75_atualizado = $this->o75_atualizado ";
       $virgula = ",";
     }
     if(trim($this->o75_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_percentual"])){ 
       $sql  .= $virgula." o75_percentual = $this->o75_percentual ";
       $virgula = ",";
       if(trim($this->o75_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "o75_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o75_valorduplicar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_valorduplicar"])){ 
       $sql  .= $virgula." o75_valorduplicar = $this->o75_valorduplicar ";
       $virgula = ",";
       if(trim($this->o75_valorduplicar) == null ){ 
         $this->erro_sql = " Campo Valor a Duplicar nao Informado.";
         $this->erro_campo = "o75_valorduplicar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o75_importar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o75_importar"])){ 
       $sql  .= $virgula." o75_importar = '$this->o75_importar' ";
       $virgula = ",";
       if(trim($this->o75_importar) == null ){ 
         $this->erro_sql = " Campo Importar nao Informado.";
         $this->erro_campo = "o75_importar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o75_sequencial!=null){
       $sql .= " o75_sequencial = $this->o75_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o75_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10466,'$this->o75_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1809,10466,'".AddSlashes(pg_result($resaco,$conresaco,'o75_sequencial'))."','$this->o75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_conaberturaexe"]))
           $resac = db_query("insert into db_acount values($acount,1809,10467,'".AddSlashes(pg_result($resaco,$conresaco,'o75_conaberturaexe'))."','$this->o75_conaberturaexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1809,10469,'".AddSlashes(pg_result($resaco,$conresaco,'o75_tipo'))."','$this->o75_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_previsaoinicial"]))
           $resac = db_query("insert into db_acount values($acount,1809,10470,'".AddSlashes(pg_result($resaco,$conresaco,'o75_previsaoinicial'))."','$this->o75_previsaoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_acrescimos"]))
           $resac = db_query("insert into db_acount values($acount,1809,10471,'".AddSlashes(pg_result($resaco,$conresaco,'o75_acrescimos'))."','$this->o75_acrescimos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_reducoes"]))
           $resac = db_query("insert into db_acount values($acount,1809,10472,'".AddSlashes(pg_result($resaco,$conresaco,'o75_reducoes'))."','$this->o75_reducoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_atualizado"]))
           $resac = db_query("insert into db_acount values($acount,1809,10473,'".AddSlashes(pg_result($resaco,$conresaco,'o75_atualizado'))."','$this->o75_atualizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_percentual"]))
           $resac = db_query("insert into db_acount values($acount,1809,10499,'".AddSlashes(pg_result($resaco,$conresaco,'o75_percentual'))."','$this->o75_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_valorduplicar"]))
           $resac = db_query("insert into db_acount values($acount,1809,10474,'".AddSlashes(pg_result($resaco,$conresaco,'o75_valorduplicar'))."','$this->o75_valorduplicar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o75_importar"]))
           $resac = db_query("insert into db_acount values($acount,1809,10475,'".AddSlashes(pg_result($resaco,$conresaco,'o75_importar'))."','$this->o75_importar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Duplicação do Orçamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Duplicação do Orçamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o75_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o75_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10466,'$o75_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1809,10466,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10467,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_conaberturaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10469,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10470,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_previsaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10471,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_acrescimos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10472,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_reducoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10473,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_atualizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10499,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10474,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_valorduplicar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1809,10475,'','".AddSlashes(pg_result($resaco,$iresaco,'o75_importar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcduplicacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o75_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o75_sequencial = $o75_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Duplicação do Orçamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Duplicação do Orçamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o75_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcduplicacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcduplicacao ";
     $sql .= "      inner join conaberturaexe  on  conaberturaexe.c91_sequencial = orcduplicacao.o75_conaberturaexe";
     $sql .= "      inner join db_config  on  db_config.codigo = conaberturaexe.c91_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = conaberturaexe.c91_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($o75_sequencial!=null ){
         $sql2 .= " where orcduplicacao.o75_sequencial = $o75_sequencial "; 
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
   function sql_query_file ( $o75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcduplicacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o75_sequencial!=null ){
         $sql2 .= " where orcduplicacao.o75_sequencial = $o75_sequencial "; 
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