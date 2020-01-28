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

//MODULO: Empenho
//CLASSE DA ENTIDADE emparquivopit
class cl_emparquivopit { 
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
   var $e14_sequencial = 0; 
   var $e14_idusuario = 0; 
   var $e14_nomearquivo = null; 
   var $e14_hora = null; 
   var $e14_dtarquivo_dia = null; 
   var $e14_dtarquivo_mes = null; 
   var $e14_dtarquivo_ano = null; 
   var $e14_dtarquivo = null; 
   var $e14_situacao = null; 
   var $e14_corpoarquivo = null; 
   var $e14_dtinicial_dia = null; 
   var $e14_dtinicial_mes = null; 
   var $e14_dtinicial_ano = null; 
   var $e14_dtinicial = null; 
   var $e14_dtfinal_dia = null; 
   var $e14_dtfinal_mes = null; 
   var $e14_dtfinal_ano = null; 
   var $e14_dtfinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e14_sequencial = int4 = Código 
                 e14_idusuario = int4 = Código Usuário 
                 e14_nomearquivo = varchar(50) = Nome do Arquivo 
                 e14_hora = char(5) = Hora 
                 e14_dtarquivo = date = Data Arquivo 
                 e14_situacao = char(1) = Situação 
                 e14_corpoarquivo = text = Corpo do arquivo 
                 e14_dtinicial = date = Data Inicial 
                 e14_dtfinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_emparquivopit() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("emparquivopit"); 
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
       $this->e14_sequencial = ($this->e14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_sequencial"]:$this->e14_sequencial);
       $this->e14_idusuario = ($this->e14_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_idusuario"]:$this->e14_idusuario);
       $this->e14_nomearquivo = ($this->e14_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_nomearquivo"]:$this->e14_nomearquivo);
       $this->e14_hora = ($this->e14_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_hora"]:$this->e14_hora);
       if($this->e14_dtarquivo == ""){
         $this->e14_dtarquivo_dia = ($this->e14_dtarquivo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_dia"]:$this->e14_dtarquivo_dia);
         $this->e14_dtarquivo_mes = ($this->e14_dtarquivo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_mes"]:$this->e14_dtarquivo_mes);
         $this->e14_dtarquivo_ano = ($this->e14_dtarquivo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_ano"]:$this->e14_dtarquivo_ano);
         if($this->e14_dtarquivo_dia != ""){
            $this->e14_dtarquivo = $this->e14_dtarquivo_ano."-".$this->e14_dtarquivo_mes."-".$this->e14_dtarquivo_dia;
         }
       }
       $this->e14_situacao = ($this->e14_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_situacao"]:$this->e14_situacao);
       $this->e14_corpoarquivo = ($this->e14_corpoarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_corpoarquivo"]:$this->e14_corpoarquivo);
       if($this->e14_dtinicial == ""){
         $this->e14_dtinicial_dia = ($this->e14_dtinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_dia"]:$this->e14_dtinicial_dia);
         $this->e14_dtinicial_mes = ($this->e14_dtinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_mes"]:$this->e14_dtinicial_mes);
         $this->e14_dtinicial_ano = ($this->e14_dtinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_ano"]:$this->e14_dtinicial_ano);
         if($this->e14_dtinicial_dia != ""){
            $this->e14_dtinicial = $this->e14_dtinicial_ano."-".$this->e14_dtinicial_mes."-".$this->e14_dtinicial_dia;
         }
       }
       if($this->e14_dtfinal == ""){
         $this->e14_dtfinal_dia = ($this->e14_dtfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_dia"]:$this->e14_dtfinal_dia);
         $this->e14_dtfinal_mes = ($this->e14_dtfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_mes"]:$this->e14_dtfinal_mes);
         $this->e14_dtfinal_ano = ($this->e14_dtfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_ano"]:$this->e14_dtfinal_ano);
         if($this->e14_dtfinal_dia != ""){
            $this->e14_dtfinal = $this->e14_dtfinal_ano."-".$this->e14_dtfinal_mes."-".$this->e14_dtfinal_dia;
         }
       }
     }else{
       $this->e14_sequencial = ($this->e14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e14_sequencial"]:$this->e14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e14_sequencial){ 
      $this->atualizacampos();
     if($this->e14_idusuario == null ){ 
       $this->erro_sql = " Campo Código Usuário nao Informado.";
       $this->erro_campo = "e14_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "e14_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e14_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_dtarquivo == null ){ 
       $this->erro_sql = " Campo Data Arquivo nao Informado.";
       $this->erro_campo = "e14_dtarquivo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "e14_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_dtinicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "e14_dtinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e14_dtfinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "e14_dtfinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e14_sequencial == "" || $e14_sequencial == null ){
       $result = db_query("select nextval('emparquivopit_e14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: emparquivopit_e14_sequencial_seq do campo: e14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from emparquivopit_e14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e14_sequencial)){
         $this->erro_sql = " Campo e14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e14_sequencial = $e14_sequencial; 
       }
     }
     if(($this->e14_sequencial == null) || ($this->e14_sequencial == "") ){ 
       $this->erro_sql = " Campo e14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into emparquivopit(
                                       e14_sequencial 
                                      ,e14_idusuario 
                                      ,e14_nomearquivo 
                                      ,e14_hora 
                                      ,e14_dtarquivo 
                                      ,e14_situacao 
                                      ,e14_corpoarquivo 
                                      ,e14_dtinicial 
                                      ,e14_dtfinal 
                       )
                values (
                                $this->e14_sequencial 
                               ,$this->e14_idusuario 
                               ,'$this->e14_nomearquivo' 
                               ,'$this->e14_hora' 
                               ,".($this->e14_dtarquivo == "null" || $this->e14_dtarquivo == ""?"null":"'".$this->e14_dtarquivo."'")." 
                               ,'$this->e14_situacao' 
                               ,'$this->e14_corpoarquivo' 
                               ,".($this->e14_dtinicial == "null" || $this->e14_dtinicial == ""?"null":"'".$this->e14_dtinicial."'")." 
                               ,".($this->e14_dtfinal == "null" || $this->e14_dtfinal == ""?"null":"'".$this->e14_dtfinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "emparquivopit ($this->e14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "emparquivopit já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "emparquivopit ($this->e14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e14_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14640,'$this->e14_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2575,14640,'','".AddSlashes(pg_result($resaco,0,'e14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14641,'','".AddSlashes(pg_result($resaco,0,'e14_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14642,'','".AddSlashes(pg_result($resaco,0,'e14_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14643,'','".AddSlashes(pg_result($resaco,0,'e14_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14644,'','".AddSlashes(pg_result($resaco,0,'e14_dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14645,'','".AddSlashes(pg_result($resaco,0,'e14_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,14671,'','".AddSlashes(pg_result($resaco,0,'e14_corpoarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,15649,'','".AddSlashes(pg_result($resaco,0,'e14_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2575,15650,'','".AddSlashes(pg_result($resaco,0,'e14_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update emparquivopit set ";
     $virgula = "";
     if(trim($this->e14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_sequencial"])){ 
       $sql  .= $virgula." e14_sequencial = $this->e14_sequencial ";
       $virgula = ",";
       if(trim($this->e14_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e14_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_idusuario"])){ 
       $sql  .= $virgula." e14_idusuario = $this->e14_idusuario ";
       $virgula = ",";
       if(trim($this->e14_idusuario) == null ){ 
         $this->erro_sql = " Campo Código Usuário nao Informado.";
         $this->erro_campo = "e14_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e14_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_nomearquivo"])){ 
       $sql  .= $virgula." e14_nomearquivo = '$this->e14_nomearquivo' ";
       $virgula = ",";
       if(trim($this->e14_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "e14_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e14_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_hora"])){ 
       $sql  .= $virgula." e14_hora = '$this->e14_hora' ";
       $virgula = ",";
       if(trim($this->e14_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e14_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e14_dtarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_dia"] !="") ){ 
       $sql  .= $virgula." e14_dtarquivo = '$this->e14_dtarquivo' ";
       $virgula = ",";
       if(trim($this->e14_dtarquivo) == null ){ 
         $this->erro_sql = " Campo Data Arquivo nao Informado.";
         $this->erro_campo = "e14_dtarquivo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo_dia"])){ 
         $sql  .= $virgula." e14_dtarquivo = null ";
         $virgula = ",";
         if(trim($this->e14_dtarquivo) == null ){ 
           $this->erro_sql = " Campo Data Arquivo nao Informado.";
           $this->erro_campo = "e14_dtarquivo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e14_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_situacao"])){ 
       $sql  .= $virgula." e14_situacao = '$this->e14_situacao' ";
       $virgula = ",";
       if(trim($this->e14_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "e14_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e14_corpoarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_corpoarquivo"])){ 
       $sql  .= $virgula." e14_corpoarquivo = '$this->e14_corpoarquivo' ";
       $virgula = ",";
     }
     if(trim($this->e14_dtinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_dia"] !="") ){ 
       $sql  .= $virgula." e14_dtinicial = '$this->e14_dtinicial' ";
       $virgula = ",";
       if(trim($this->e14_dtinicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "e14_dtinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtinicial_dia"])){ 
         $sql  .= $virgula." e14_dtinicial = null ";
         $virgula = ",";
         if(trim($this->e14_dtinicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "e14_dtinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e14_dtfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_dia"] !="") ){ 
       $sql  .= $virgula." e14_dtfinal = '$this->e14_dtfinal' ";
       $virgula = ",";
       if(trim($this->e14_dtfinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "e14_dtfinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtfinal_dia"])){ 
         $sql  .= $virgula." e14_dtfinal = null ";
         $virgula = ",";
         if(trim($this->e14_dtfinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "e14_dtfinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($e14_sequencial!=null){
       $sql .= " e14_sequencial = $this->e14_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e14_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14640,'$this->e14_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_sequencial"]) || $this->e14_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2575,14640,'".AddSlashes(pg_result($resaco,$conresaco,'e14_sequencial'))."','$this->e14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_idusuario"]) || $this->e14_idusuario != "")
           $resac = db_query("insert into db_acount values($acount,2575,14641,'".AddSlashes(pg_result($resaco,$conresaco,'e14_idusuario'))."','$this->e14_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_nomearquivo"]) || $this->e14_nomearquivo != "")
           $resac = db_query("insert into db_acount values($acount,2575,14642,'".AddSlashes(pg_result($resaco,$conresaco,'e14_nomearquivo'))."','$this->e14_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_hora"]) || $this->e14_hora != "")
           $resac = db_query("insert into db_acount values($acount,2575,14643,'".AddSlashes(pg_result($resaco,$conresaco,'e14_hora'))."','$this->e14_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtarquivo"]) || $this->e14_dtarquivo != "")
           $resac = db_query("insert into db_acount values($acount,2575,14644,'".AddSlashes(pg_result($resaco,$conresaco,'e14_dtarquivo'))."','$this->e14_dtarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_situacao"]) || $this->e14_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2575,14645,'".AddSlashes(pg_result($resaco,$conresaco,'e14_situacao'))."','$this->e14_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_corpoarquivo"]) || $this->e14_corpoarquivo != "")
           $resac = db_query("insert into db_acount values($acount,2575,14671,'".AddSlashes(pg_result($resaco,$conresaco,'e14_corpoarquivo'))."','$this->e14_corpoarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtinicial"]) || $this->e14_dtinicial != "")
           $resac = db_query("insert into db_acount values($acount,2575,15649,'".AddSlashes(pg_result($resaco,$conresaco,'e14_dtinicial'))."','$this->e14_dtinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e14_dtfinal"]) || $this->e14_dtfinal != "")
           $resac = db_query("insert into db_acount values($acount,2575,15650,'".AddSlashes(pg_result($resaco,$conresaco,'e14_dtfinal'))."','$this->e14_dtfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "emparquivopit nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "emparquivopit nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e14_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e14_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14640,'$e14_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2575,14640,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14641,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14642,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14643,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14644,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14645,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,14671,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_corpoarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,15649,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2575,15650,'','".AddSlashes(pg_result($resaco,$iresaco,'e14_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from emparquivopit
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e14_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e14_sequencial = $e14_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "emparquivopit nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "emparquivopit nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:emparquivopit";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emparquivopit ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emparquivopit.e14_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($e14_sequencial!=null ){
         $sql2 .= " where emparquivopit.e14_sequencial = $e14_sequencial "; 
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
   function sql_query_file ( $e14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emparquivopit ";
     $sql2 = "";
     if($dbwhere==""){
       if($e14_sequencial!=null ){
         $sql2 .= " where emparquivopit.e14_sequencial = $e14_sequencial "; 
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
  
  function sql_query_ativo ( $e14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emparquivopit ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emparquivopit.e14_idusuario";
     $sql .= "      left join emparquivopitanulado on  emparquivopitanulado.e16_emparquivopit = emparquivopit.e14_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($e14_sequencial!=null ){
         $sql2 .= " where emparquivopit.e14_sequencial = $e14_sequencial "; 
         $sql2 .= "   and emparquivopitanulado.e16_sequencial is null "; 
       }else{
       	 $sql2 .= "   where emparquivopitanulado.e16_sequencial is null ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
       $sql2 .= "   and emparquivopitanulado.e16_sequencial is null ";
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