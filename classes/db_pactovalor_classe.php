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
//CLASSE DA ENTIDADE pactovalor
class cl_pactovalor { 
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
   var $o87_sequencial = 0; 
   var $o87_pactoplano = 0; 
   var $o87_pactoprograma = 0; 
   var $o87_orcprojativativprojeto = 0; 
   var $o87_orcprojativanoprojeto = 0; 
   var $o87_pactoatividade = 0; 
   var $o87_pactoacoes = 0; 
   var $o87_categoriapacto = 0; 
   var $o87_pactoitem = 0; 
   var $o87_quantidade = 0; 
   var $o87_vlraproximado = 0; 
   var $o87_orcprogramaano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o87_sequencial = int4 = Sequencial 
                 o87_pactoplano = int4 = Código do Plano 
                 o87_pactoprograma = int4 = Programa 
                 o87_orcprojativativprojeto = int4 = Projeto 
                 o87_orcprojativanoprojeto = int4 = Ano Projeto 
                 o87_pactoatividade = int4 = Atividade 
                 o87_pactoacoes = int8 = Ações 
                 o87_categoriapacto = int8 = Categoria 
                 o87_pactoitem = int4 = Código do Item 
                 o87_quantidade = float8 = Quantidade 
                 o87_vlraproximado = float8 = Valor Aproximado 
                 o87_orcprogramaano = int4 = Ano do Porgrama 
                 ";
   //funcao construtor da classe 
   function cl_pactovalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pactovalor"); 
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
       $this->o87_sequencial = ($this->o87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_sequencial"]:$this->o87_sequencial);
       $this->o87_pactoplano = ($this->o87_pactoplano == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_pactoplano"]:$this->o87_pactoplano);
       $this->o87_pactoprograma = ($this->o87_pactoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_pactoprograma"]:$this->o87_pactoprograma);
       $this->o87_orcprojativativprojeto = ($this->o87_orcprojativativprojeto == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_orcprojativativprojeto"]:$this->o87_orcprojativativprojeto);
       $this->o87_orcprojativanoprojeto = ($this->o87_orcprojativanoprojeto == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_orcprojativanoprojeto"]:$this->o87_orcprojativanoprojeto);
       $this->o87_pactoatividade = ($this->o87_pactoatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_pactoatividade"]:$this->o87_pactoatividade);
       $this->o87_pactoacoes = ($this->o87_pactoacoes == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_pactoacoes"]:$this->o87_pactoacoes);
       $this->o87_categoriapacto = ($this->o87_categoriapacto == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_categoriapacto"]:$this->o87_categoriapacto);
       $this->o87_pactoitem = ($this->o87_pactoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_pactoitem"]:$this->o87_pactoitem);
       $this->o87_quantidade = ($this->o87_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_quantidade"]:$this->o87_quantidade);
       $this->o87_vlraproximado = ($this->o87_vlraproximado == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_vlraproximado"]:$this->o87_vlraproximado);
       $this->o87_orcprogramaano = ($this->o87_orcprogramaano == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_orcprogramaano"]:$this->o87_orcprogramaano);
     }else{
       $this->o87_sequencial = ($this->o87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o87_sequencial"]:$this->o87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o87_sequencial){ 
      $this->atualizacampos();
     if($this->o87_pactoplano == null ){ 
       $this->erro_sql = " Campo Código do Plano nao Informado.";
       $this->erro_campo = "o87_pactoplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o87_pactoprograma == null ){ 
       $this->o87_pactoprograma = "null";
     }
     if($this->o87_orcprojativativprojeto == null ){ 
       $this->o87_orcprojativativprojeto = "null";
     }
     if($this->o87_orcprojativanoprojeto == null ){ 
       $this->o87_orcprojativanoprojeto = "null";
     }
     if($this->o87_pactoatividade == null ){ 
       $this->o87_pactoatividade = "null";
     }
     if($this->o87_pactoacoes == null ){ 
       $this->o87_pactoacoes = "null";
     }
     if($this->o87_categoriapacto == null ){ 
       $this->o87_categoriapacto = "null";
     }
     if($this->o87_pactoitem == null ){ 
       $this->o87_pactoitem = "null";
     }
     if($this->o87_quantidade == null ){ 
       $this->o87_quantidade = "null";
     }
     if($this->o87_vlraproximado == null ){ 
       $this->erro_sql = " Campo Valor Aproximado nao Informado.";
       $this->erro_campo = "o87_vlraproximado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o87_orcprogramaano == null ){ 
       $this->o87_orcprogramaano = "null";
     }
     if($o87_sequencial == "" || $o87_sequencial == null ){
       $result = db_query("select nextval('pactovalor_o87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pactovalor_o87_sequencial_seq do campo: o87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pactovalor_o87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o87_sequencial)){
         $this->erro_sql = " Campo o87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o87_sequencial = $o87_sequencial; 
       }
     }
     if(($this->o87_sequencial == null) || ($this->o87_sequencial == "") ){ 
       $this->erro_sql = " Campo o87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pactovalor(
                                       o87_sequencial 
                                      ,o87_pactoplano 
                                      ,o87_pactoprograma 
                                      ,o87_orcprojativativprojeto 
                                      ,o87_orcprojativanoprojeto 
                                      ,o87_pactoatividade 
                                      ,o87_pactoacoes 
                                      ,o87_categoriapacto 
                                      ,o87_pactoitem 
                                      ,o87_quantidade 
                                      ,o87_vlraproximado 
                                      ,o87_orcprogramaano 
                       )
                values (
                                $this->o87_sequencial 
                               ,$this->o87_pactoplano 
                               ,$this->o87_pactoprograma 
                               ,$this->o87_orcprojativativprojeto 
                               ,$this->o87_orcprojativanoprojeto 
                               ,$this->o87_pactoatividade 
                               ,$this->o87_pactoacoes 
                               ,$this->o87_categoriapacto 
                               ,$this->o87_pactoitem 
                               ,$this->o87_quantidade 
                               ,$this->o87_vlraproximado 
                               ,$this->o87_orcprogramaano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ações do Pacto ($this->o87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ações do Pacto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ações do Pacto ($this->o87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13911,'$this->o87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2446,13911,'','".AddSlashes(pg_result($resaco,0,'o87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13966,'','".AddSlashes(pg_result($resaco,0,'o87_pactoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13976,'','".AddSlashes(pg_result($resaco,0,'o87_pactoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13982,'','".AddSlashes(pg_result($resaco,0,'o87_orcprojativativprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13983,'','".AddSlashes(pg_result($resaco,0,'o87_orcprojativanoprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13977,'','".AddSlashes(pg_result($resaco,0,'o87_pactoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13925,'','".AddSlashes(pg_result($resaco,0,'o87_pactoacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13923,'','".AddSlashes(pg_result($resaco,0,'o87_categoriapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13965,'','".AddSlashes(pg_result($resaco,0,'o87_pactoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13926,'','".AddSlashes(pg_result($resaco,0,'o87_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,13927,'','".AddSlashes(pg_result($resaco,0,'o87_vlraproximado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2446,14049,'','".AddSlashes(pg_result($resaco,0,'o87_orcprogramaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pactovalor set ";
     $virgula = "";
     if(trim($this->o87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_sequencial"])){ 
       $sql  .= $virgula." o87_sequencial = $this->o87_sequencial ";
       $virgula = ",";
       if(trim($this->o87_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o87_pactoplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoplano"])){ 
       $sql  .= $virgula." o87_pactoplano = $this->o87_pactoplano ";
       $virgula = ",";
       if(trim($this->o87_pactoplano) == null ){ 
         $this->erro_sql = " Campo Código do Plano nao Informado.";
         $this->erro_campo = "o87_pactoplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o87_pactoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoprograma"])){ 
        if(trim($this->o87_pactoprograma)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoprograma"])){ 
           $this->o87_pactoprograma = "0" ; 
        } 
       $sql  .= $virgula." o87_pactoprograma = $this->o87_pactoprograma ";
       $virgula = ",";
     }
     if(trim($this->o87_orcprojativativprojeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativativprojeto"])){ 
        if(trim($this->o87_orcprojativativprojeto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativativprojeto"])){ 
           $this->o87_orcprojativativprojeto = "null" ; 
        } 
       $sql  .= $virgula." o87_orcprojativativprojeto = $this->o87_orcprojativativprojeto ";
       $virgula = ",";
     }
     if(trim($this->o87_orcprojativanoprojeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativanoprojeto"])){ 
        if(trim($this->o87_orcprojativanoprojeto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativanoprojeto"])){ 
           $this->o87_orcprojativanoprojeto = "null" ; 
        } 
       $sql  .= $virgula." o87_orcprojativanoprojeto = $this->o87_orcprojativanoprojeto ";
       $virgula = ",";
     }
     if(trim($this->o87_pactoatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoatividade"])){ 
        if(trim($this->o87_pactoatividade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoatividade"])){ 
           $this->o87_pactoatividade = "null" ; 
        } 
       $sql  .= $virgula." o87_pactoatividade = $this->o87_pactoatividade ";
       $virgula = ",";
     }
     if(trim($this->o87_pactoacoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoacoes"])){ 
        if(trim($this->o87_pactoacoes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoacoes"])){ 
           $this->o87_pactoacoes = "null" ; 
        } 
       $sql  .= $virgula." o87_pactoacoes = $this->o87_pactoacoes ";
       $virgula = ",";
     }
     if(trim($this->o87_categoriapacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_categoriapacto"])){ 
        if(trim($this->o87_categoriapacto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_categoriapacto"])){ 
           $this->o87_categoriapacto = "null" ; 
        } 
       $sql  .= $virgula." o87_categoriapacto = $this->o87_categoriapacto ";
       $virgula = ",";
     }
     if(trim($this->o87_pactoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoitem"])){ 
        if(trim($this->o87_pactoitem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoitem"])){ 
           $this->o87_pactoitem = "null" ; 
        } 
       $sql  .= $virgula." o87_pactoitem = $this->o87_pactoitem ";
       $virgula = ",";
     }
     if(trim($this->o87_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_quantidade"])){ 
        if(trim($this->o87_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_quantidade"])){ 
           $this->o87_quantidade = "null" ; 
        } 
       $sql  .= $virgula." o87_quantidade = $this->o87_quantidade ";
       $virgula = ",";
     }
     if(trim($this->o87_vlraproximado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_vlraproximado"])){ 
       $sql  .= $virgula." o87_vlraproximado = $this->o87_vlraproximado ";
       $virgula = ",";
       if(trim($this->o87_vlraproximado) == null ){ 
         $this->erro_sql = " Campo Valor Aproximado nao Informado.";
         $this->erro_campo = "o87_vlraproximado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o87_orcprogramaano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprogramaano"])){ 
        if(trim($this->o87_orcprogramaano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprogramaano"])){ 
           $this->o87_orcprogramaano = "null" ; 
        } 
       $sql  .= $virgula." o87_orcprogramaano = $this->o87_orcprogramaano ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o87_sequencial!=null){
       $sql .= " o87_sequencial = $this->o87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13911,'$this->o87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_sequencial"]) || $this->o87_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2446,13911,'".AddSlashes(pg_result($resaco,$conresaco,'o87_sequencial'))."','$this->o87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoplano"]) || $this->o87_pactoplano != "")
           $resac = db_query("insert into db_acount values($acount,2446,13966,'".AddSlashes(pg_result($resaco,$conresaco,'o87_pactoplano'))."','$this->o87_pactoplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoprograma"]) || $this->o87_pactoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2446,13976,'".AddSlashes(pg_result($resaco,$conresaco,'o87_pactoprograma'))."','$this->o87_pactoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativativprojeto"]) || $this->o87_orcprojativativprojeto != "")
           $resac = db_query("insert into db_acount values($acount,2446,13982,'".AddSlashes(pg_result($resaco,$conresaco,'o87_orcprojativativprojeto'))."','$this->o87_orcprojativativprojeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprojativanoprojeto"]) || $this->o87_orcprojativanoprojeto != "")
           $resac = db_query("insert into db_acount values($acount,2446,13983,'".AddSlashes(pg_result($resaco,$conresaco,'o87_orcprojativanoprojeto'))."','$this->o87_orcprojativanoprojeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoatividade"]) || $this->o87_pactoatividade != "")
           $resac = db_query("insert into db_acount values($acount,2446,13977,'".AddSlashes(pg_result($resaco,$conresaco,'o87_pactoatividade'))."','$this->o87_pactoatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoacoes"]) || $this->o87_pactoacoes != "")
           $resac = db_query("insert into db_acount values($acount,2446,13925,'".AddSlashes(pg_result($resaco,$conresaco,'o87_pactoacoes'))."','$this->o87_pactoacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_categoriapacto"]) || $this->o87_categoriapacto != "")
           $resac = db_query("insert into db_acount values($acount,2446,13923,'".AddSlashes(pg_result($resaco,$conresaco,'o87_categoriapacto'))."','$this->o87_categoriapacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_pactoitem"]) || $this->o87_pactoitem != "")
           $resac = db_query("insert into db_acount values($acount,2446,13965,'".AddSlashes(pg_result($resaco,$conresaco,'o87_pactoitem'))."','$this->o87_pactoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_quantidade"]) || $this->o87_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2446,13926,'".AddSlashes(pg_result($resaco,$conresaco,'o87_quantidade'))."','$this->o87_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_vlraproximado"]) || $this->o87_vlraproximado != "")
           $resac = db_query("insert into db_acount values($acount,2446,13927,'".AddSlashes(pg_result($resaco,$conresaco,'o87_vlraproximado'))."','$this->o87_vlraproximado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o87_orcprogramaano"]) || $this->o87_orcprogramaano != "")
           $resac = db_query("insert into db_acount values($acount,2446,14049,'".AddSlashes(pg_result($resaco,$conresaco,'o87_orcprogramaano'))."','$this->o87_orcprogramaano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ações do Pacto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ações do Pacto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13911,'$o87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2446,13911,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13966,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_pactoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13976,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_pactoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13982,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_orcprojativativprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13983,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_orcprojativanoprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13977,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_pactoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13925,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_pactoacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13923,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_categoriapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13965,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_pactoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13926,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,13927,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_vlraproximado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2446,14049,'','".AddSlashes(pg_result($resaco,$iresaco,'o87_orcprogramaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pactovalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o87_sequencial = $o87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ações do Pacto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ações do Pacto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pactovalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pactovalor ";
     $sql .= "      inner join pactoplano          on pactoplano.o74_sequencial         = pactovalor.o87_pactoplano";
     $sql .= "      inner join orctiporecconvenio  on orctiporecconvenio.o16_sequencial = pactoplano.o74_orctiporecconvenio";
     $sql .= "      left  join orcprojativ         on orcprojativ.o55_anousu            = pactovalor.o87_orcprojativanoprojeto 
                                                  and orcprojativ.o55_projativ          = pactovalor.o87_orcprojativativprojeto";
     $sql .= "      left  join orcprograma         on  orcprograma.o54_anousu           = pactovalor.o87_orcprogramaano 
                                                  and  orcprograma.o54_programa         = pactovalor.o87_pactoprograma";
     $sql .= "      left  join pactoacoes          on pactoacoes.o79_sequencial         = pactovalor.o87_pactoacoes";
     $sql .= "      left  join categoriapacto      on categoriapacto.o31_sequencial     = pactovalor.o87_categoriapacto";
     $sql .= "      left  join pactoitem           on pactoitem.o109_sequencial         = pactovalor.o87_pactoitem";
     $sql .= "      left  join pactoatividade      on pactoatividade.o104_sequencial    = pactovalor.o87_pactoatividade";
     $sql .= "      left  join pactoprograma       on pactoprograma.o107_sequencial     = pactovalor.o87_pactoprograma";
     $sql .= "      left  join db_config           on db_config.codigo                  = orcprojativ.o55_instit";
     $sql .= "      left  join tipopacto           on tipopacto.o29_sequencial          = categoriapacto.o31_tipopacto";
     $sql .= "      left  join matunid             on matunid.m61_codmatunid            = pactoitem.o109_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($o87_sequencial!=null ){
         $sql2 .= " where pactovalor.o87_sequencial = $o87_sequencial "; 
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
   function sql_query_file ( $o87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pactovalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($o87_sequencial!=null ){
         $sql2 .= " where pactovalor.o87_sequencial = $o87_sequencial "; 
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