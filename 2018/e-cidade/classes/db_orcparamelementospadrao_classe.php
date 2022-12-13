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
//CLASSE DA ENTIDADE orcparamelementospadrao
class cl_orcparamelementospadrao { 
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
   var $o132_sequencial = 0; 
   var $o132_orcparamrel = 0; 
   var $o132_orcparamseq = 0; 
   var $o132_orcelemento = 0; 
   var $o132_anousu = 0; 
   var $o132_instit = 0; 
   var $o132_exclusao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o132_sequencial = int4 = C�digo Sequencial 
                 o132_orcparamrel = int4 = C�digo do Relat�rio 
                 o132_orcparamseq = int4 = Sequencia da Linha 
                 o132_orcelemento = int4 = C�digo do Elemento 
                 o132_anousu = int4 = Ano da Configura��o 
                 o132_instit = int4 = Institui��o 
                 o132_exclusao = bool = Elemento de Exclus�o 
                 ";
   //funcao construtor da classe 
   function cl_orcparamelementospadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamelementospadrao"); 
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
       $this->o132_sequencial = ($this->o132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_sequencial"]:$this->o132_sequencial);
       $this->o132_orcparamrel = ($this->o132_orcparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_orcparamrel"]:$this->o132_orcparamrel);
       $this->o132_orcparamseq = ($this->o132_orcparamseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_orcparamseq"]:$this->o132_orcparamseq);
       $this->o132_orcelemento = ($this->o132_orcelemento == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_orcelemento"]:$this->o132_orcelemento);
       $this->o132_anousu = ($this->o132_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_anousu"]:$this->o132_anousu);
       $this->o132_instit = ($this->o132_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_instit"]:$this->o132_instit);
       $this->o132_exclusao = ($this->o132_exclusao == "f"?@$GLOBALS["HTTP_POST_VARS"]["o132_exclusao"]:$this->o132_exclusao);
     }else{
       $this->o132_sequencial = ($this->o132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o132_sequencial"]:$this->o132_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o132_sequencial){ 
      $this->atualizacampos();
     if($this->o132_orcparamrel == null ){ 
       $this->erro_sql = " Campo C�digo do Relat�rio nao Informado.";
       $this->erro_campo = "o132_orcparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o132_orcparamseq == null ){ 
       $this->erro_sql = " Campo Sequencia da Linha nao Informado.";
       $this->erro_campo = "o132_orcparamseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o132_orcelemento == null ){ 
       $this->erro_sql = " Campo C�digo do Elemento nao Informado.";
       $this->erro_campo = "o132_orcelemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o132_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Configura��o nao Informado.";
       $this->erro_campo = "o132_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o132_instit == null ){ 
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "o132_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o132_exclusao == null ){ 
       $this->erro_sql = " Campo Elemento de Exclus�o nao Informado.";
       $this->erro_campo = "o132_exclusao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o132_sequencial == "" || $o132_sequencial == null ){
       $result = db_query("select nextval('orcparamelementospadrao_o132_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamelementospadrao_o132_sequencial_seq do campo: o132_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o132_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamelementospadrao_o132_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o132_sequencial)){
         $this->erro_sql = " Campo o132_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o132_sequencial = $o132_sequencial; 
       }
     }
     if(($this->o132_sequencial == null) || ($this->o132_sequencial == "") ){ 
       $this->erro_sql = " Campo o132_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamelementospadrao(
                                       o132_sequencial 
                                      ,o132_orcparamrel 
                                      ,o132_orcparamseq 
                                      ,o132_orcelemento 
                                      ,o132_anousu 
                                      ,o132_instit 
                                      ,o132_exclusao 
                       )
                values (
                                $this->o132_sequencial 
                               ,$this->o132_orcparamrel 
                               ,$this->o132_orcparamseq 
                               ,$this->o132_orcelemento 
                               ,$this->o132_anousu 
                               ,$this->o132_instit 
                               ,'$this->o132_exclusao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configura��o Padrao dos Elementos ($this->o132_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configura��o Padrao dos Elementos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configura��o Padrao dos Elementos ($this->o132_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o132_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o132_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15417,'$this->o132_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2706,15417,'','".AddSlashes(pg_result($resaco,0,'o132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15418,'','".AddSlashes(pg_result($resaco,0,'o132_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15419,'','".AddSlashes(pg_result($resaco,0,'o132_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15420,'','".AddSlashes(pg_result($resaco,0,'o132_orcelemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15421,'','".AddSlashes(pg_result($resaco,0,'o132_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15422,'','".AddSlashes(pg_result($resaco,0,'o132_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2706,15423,'','".AddSlashes(pg_result($resaco,0,'o132_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o132_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamelementospadrao set ";
     $virgula = "";
     if(trim($this->o132_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_sequencial"])){ 
       $sql  .= $virgula." o132_sequencial = $this->o132_sequencial ";
       $virgula = ",";
       if(trim($this->o132_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "o132_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_orcparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_orcparamrel"])){ 
       $sql  .= $virgula." o132_orcparamrel = $this->o132_orcparamrel ";
       $virgula = ",";
       if(trim($this->o132_orcparamrel) == null ){ 
         $this->erro_sql = " Campo C�digo do Relat�rio nao Informado.";
         $this->erro_campo = "o132_orcparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_orcparamseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_orcparamseq"])){ 
       $sql  .= $virgula." o132_orcparamseq = $this->o132_orcparamseq ";
       $virgula = ",";
       if(trim($this->o132_orcparamseq) == null ){ 
         $this->erro_sql = " Campo Sequencia da Linha nao Informado.";
         $this->erro_campo = "o132_orcparamseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_orcelemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_orcelemento"])){ 
       $sql  .= $virgula." o132_orcelemento = $this->o132_orcelemento ";
       $virgula = ",";
       if(trim($this->o132_orcelemento) == null ){ 
         $this->erro_sql = " Campo C�digo do Elemento nao Informado.";
         $this->erro_campo = "o132_orcelemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_anousu"])){ 
       $sql  .= $virgula." o132_anousu = $this->o132_anousu ";
       $virgula = ",";
       if(trim($this->o132_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Configura��o nao Informado.";
         $this->erro_campo = "o132_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_instit"])){ 
       $sql  .= $virgula." o132_instit = $this->o132_instit ";
       $virgula = ",";
       if(trim($this->o132_instit) == null ){ 
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "o132_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o132_exclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o132_exclusao"])){ 
       $sql  .= $virgula." o132_exclusao = '$this->o132_exclusao' ";
       $virgula = ",";
       if(trim($this->o132_exclusao) == null ){ 
         $this->erro_sql = " Campo Elemento de Exclus�o nao Informado.";
         $this->erro_campo = "o132_exclusao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o132_sequencial!=null){
       $sql .= " o132_sequencial = $this->o132_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o132_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15417,'$this->o132_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_sequencial"]) || $this->o132_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2706,15417,'".AddSlashes(pg_result($resaco,$conresaco,'o132_sequencial'))."','$this->o132_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_orcparamrel"]) || $this->o132_orcparamrel != "")
           $resac = db_query("insert into db_acount values($acount,2706,15418,'".AddSlashes(pg_result($resaco,$conresaco,'o132_orcparamrel'))."','$this->o132_orcparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_orcparamseq"]) || $this->o132_orcparamseq != "")
           $resac = db_query("insert into db_acount values($acount,2706,15419,'".AddSlashes(pg_result($resaco,$conresaco,'o132_orcparamseq'))."','$this->o132_orcparamseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_orcelemento"]) || $this->o132_orcelemento != "")
           $resac = db_query("insert into db_acount values($acount,2706,15420,'".AddSlashes(pg_result($resaco,$conresaco,'o132_orcelemento'))."','$this->o132_orcelemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_anousu"]) || $this->o132_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2706,15421,'".AddSlashes(pg_result($resaco,$conresaco,'o132_anousu'))."','$this->o132_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_instit"]) || $this->o132_instit != "")
           $resac = db_query("insert into db_acount values($acount,2706,15422,'".AddSlashes(pg_result($resaco,$conresaco,'o132_instit'))."','$this->o132_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o132_exclusao"]) || $this->o132_exclusao != "")
           $resac = db_query("insert into db_acount values($acount,2706,15423,'".AddSlashes(pg_result($resaco,$conresaco,'o132_exclusao'))."','$this->o132_exclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configura��o Padrao dos Elementos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o132_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o Padrao dos Elementos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o132_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o132_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o132_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o132_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15417,'$o132_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2706,15417,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15418,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15419,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15420,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_orcelemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15421,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15422,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2706,15423,'','".AddSlashes(pg_result($resaco,$iresaco,'o132_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamelementospadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o132_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o132_sequencial = $o132_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configura��o Padrao dos Elementos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o132_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o Padrao dos Elementos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o132_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o132_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orcparamelementospadrao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamelementospadrao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcparamelementospadrao.o132_instit";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamelementospadrao.o132_orcparamrel and  orcparamseq.o69_codseq = orcparamelementospadrao.o132_orcparamseq";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o132_sequencial!=null ){
         $sql2 .= " where orcparamelementospadrao.o132_sequencial = $o132_sequencial "; 
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
   function sql_query_file ( $o132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamelementospadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o132_sequencial!=null ){
         $sql2 .= " where orcparamelementospadrao.o132_sequencial = $o132_sequencial "; 
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