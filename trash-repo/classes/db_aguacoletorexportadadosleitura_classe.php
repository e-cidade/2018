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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacoletorexportadadosleitura
class cl_aguacoletorexportadadosleitura { 
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
   var $x51_sequencial = 0; 
   var $x51_aguacoletorexportadados = 0; 
   var $x51_agualeitura = 0; 
   var $x51_diasultimaleitura = 0; 
   var $x51_mesesultimaleitura = 0; 
   var $x51_tipoleitura = 0; 
   var $x51_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x51_sequencial = int4 = Código Leitura 
                 x51_aguacoletorexportadados = int8 = Código Exportação Dados 
                 x51_agualeitura = int4 = Codigo 
                 x51_diasultimaleitura = int4 = Dias Ultima Leitura 
                 x51_mesesultimaleitura = int4 = Mês Última Leitura 
                 x51_tipoleitura = int4 = Tipo de Leitura 
                 x51_numcgm = int4 = Leiturista 
                 ";
   //funcao construtor da classe 
   function cl_aguacoletorexportadadosleitura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacoletorexportadadosleitura"); 
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
       $this->x51_sequencial = ($this->x51_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_sequencial"]:$this->x51_sequencial);
       $this->x51_aguacoletorexportadados = ($this->x51_aguacoletorexportadados == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_aguacoletorexportadados"]:$this->x51_aguacoletorexportadados);
       $this->x51_agualeitura = ($this->x51_agualeitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_agualeitura"]:$this->x51_agualeitura);
       $this->x51_diasultimaleitura = ($this->x51_diasultimaleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_diasultimaleitura"]:$this->x51_diasultimaleitura);
       $this->x51_mesesultimaleitura = ($this->x51_mesesultimaleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_mesesultimaleitura"]:$this->x51_mesesultimaleitura);
       $this->x51_tipoleitura = ($this->x51_tipoleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_tipoleitura"]:$this->x51_tipoleitura);
       $this->x51_numcgm = ($this->x51_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_numcgm"]:$this->x51_numcgm);
     }else{
       $this->x51_sequencial = ($this->x51_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x51_sequencial"]:$this->x51_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($x51_sequencial){ 
      $this->atualizacampos();
     if($this->x51_aguacoletorexportadados == null ){ 
       $this->erro_sql = " Campo Código Exportação Dados nao Informado.";
       $this->erro_campo = "x51_aguacoletorexportadados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x51_agualeitura == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "x51_agualeitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x51_diasultimaleitura == null ){ 
       $this->erro_sql = " Campo Dias Ultima Leitura nao Informado.";
       $this->erro_campo = "x51_diasultimaleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x51_mesesultimaleitura == null ){ 
       $this->erro_sql = " Campo Mês Última Leitura nao Informado.";
       $this->erro_campo = "x51_mesesultimaleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x51_tipoleitura == null ){ 
       $this->erro_sql = " Campo Tipo de Leitura nao Informado.";
       $this->erro_campo = "x51_tipoleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x51_numcgm == null ){ 
       $this->x51_numcgm = "0";
     }
     if($x51_sequencial == "" || $x51_sequencial == null ){
       $result = db_query("select nextval('aguacoletorexportadadosleitura_x51_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacoletorexportadadosleitura_x51_sequencial_seq do campo: x51_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x51_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacoletorexportadadosleitura_x51_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x51_sequencial)){
         $this->erro_sql = " Campo x51_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x51_sequencial = $x51_sequencial; 
       }
     }
     if(($this->x51_sequencial == null) || ($this->x51_sequencial == "") ){ 
       $this->erro_sql = " Campo x51_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacoletorexportadadosleitura(
                                       x51_sequencial 
                                      ,x51_aguacoletorexportadados 
                                      ,x51_agualeitura 
                                      ,x51_diasultimaleitura 
                                      ,x51_mesesultimaleitura 
                                      ,x51_tipoleitura 
                                      ,x51_numcgm 
                       )
                values (
                                $this->x51_sequencial 
                               ,$this->x51_aguacoletorexportadados 
                               ,$this->x51_agualeitura 
                               ,$this->x51_diasultimaleitura 
                               ,$this->x51_mesesultimaleitura 
                               ,$this->x51_tipoleitura 
                               ,$this->x51_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Coletor Exporta Dados Leitura ($this->x51_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Coletor Exporta Dados Leitura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Coletor Exporta Dados Leitura ($this->x51_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x51_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x51_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15406,'$this->x51_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2705,15406,'','".AddSlashes(pg_result($resaco,0,'x51_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,15407,'','".AddSlashes(pg_result($resaco,0,'x51_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,15408,'','".AddSlashes(pg_result($resaco,0,'x51_agualeitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,15409,'','".AddSlashes(pg_result($resaco,0,'x51_diasultimaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,15410,'','".AddSlashes(pg_result($resaco,0,'x51_mesesultimaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,15411,'','".AddSlashes(pg_result($resaco,0,'x51_tipoleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2705,16565,'','".AddSlashes(pg_result($resaco,0,'x51_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x51_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update aguacoletorexportadadosleitura set ";
     $virgula = "";
     if(trim($this->x51_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_sequencial"])){ 
       $sql  .= $virgula." x51_sequencial = $this->x51_sequencial ";
       $virgula = ",";
       if(trim($this->x51_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Leitura nao Informado.";
         $this->erro_campo = "x51_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_aguacoletorexportadados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_aguacoletorexportadados"])){ 
       $sql  .= $virgula." x51_aguacoletorexportadados = $this->x51_aguacoletorexportadados ";
       $virgula = ",";
       if(trim($this->x51_aguacoletorexportadados) == null ){ 
         $this->erro_sql = " Campo Código Exportação Dados nao Informado.";
         $this->erro_campo = "x51_aguacoletorexportadados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_agualeitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_agualeitura"])){ 
       $sql  .= $virgula." x51_agualeitura = $this->x51_agualeitura ";
       $virgula = ",";
       if(trim($this->x51_agualeitura) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x51_agualeitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_diasultimaleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_diasultimaleitura"])){ 
       $sql  .= $virgula." x51_diasultimaleitura = $this->x51_diasultimaleitura ";
       $virgula = ",";
       if(trim($this->x51_diasultimaleitura) == null ){ 
         $this->erro_sql = " Campo Dias Ultima Leitura nao Informado.";
         $this->erro_campo = "x51_diasultimaleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_mesesultimaleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_mesesultimaleitura"])){ 
       $sql  .= $virgula." x51_mesesultimaleitura = $this->x51_mesesultimaleitura ";
       $virgula = ",";
       if(trim($this->x51_mesesultimaleitura) == null ){ 
         $this->erro_sql = " Campo Mês Última Leitura nao Informado.";
         $this->erro_campo = "x51_mesesultimaleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_tipoleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_tipoleitura"])){ 
       $sql  .= $virgula." x51_tipoleitura = $this->x51_tipoleitura ";
       $virgula = ",";
       if(trim($this->x51_tipoleitura) == null ){ 
         $this->erro_sql = " Campo Tipo de Leitura nao Informado.";
         $this->erro_campo = "x51_tipoleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x51_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x51_numcgm"])){ 
        if(trim($this->x51_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x51_numcgm"])){ 
           $this->x51_numcgm = "0" ; 
        } 
       $sql  .= $virgula." x51_numcgm = $this->x51_numcgm ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x51_sequencial!=null){
       $sql .= " x51_sequencial = $this->x51_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x51_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15406,'$this->x51_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_sequencial"]) || $this->x51_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2705,15406,'".AddSlashes(pg_result($resaco,$conresaco,'x51_sequencial'))."','$this->x51_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_aguacoletorexportadados"]) || $this->x51_aguacoletorexportadados != "")
           $resac = db_query("insert into db_acount values($acount,2705,15407,'".AddSlashes(pg_result($resaco,$conresaco,'x51_aguacoletorexportadados'))."','$this->x51_aguacoletorexportadados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_agualeitura"]) || $this->x51_agualeitura != "")
           $resac = db_query("insert into db_acount values($acount,2705,15408,'".AddSlashes(pg_result($resaco,$conresaco,'x51_agualeitura'))."','$this->x51_agualeitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_diasultimaleitura"]) || $this->x51_diasultimaleitura != "")
           $resac = db_query("insert into db_acount values($acount,2705,15409,'".AddSlashes(pg_result($resaco,$conresaco,'x51_diasultimaleitura'))."','$this->x51_diasultimaleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_mesesultimaleitura"]) || $this->x51_mesesultimaleitura != "")
           $resac = db_query("insert into db_acount values($acount,2705,15410,'".AddSlashes(pg_result($resaco,$conresaco,'x51_mesesultimaleitura'))."','$this->x51_mesesultimaleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_tipoleitura"]) || $this->x51_tipoleitura != "")
           $resac = db_query("insert into db_acount values($acount,2705,15411,'".AddSlashes(pg_result($resaco,$conresaco,'x51_tipoleitura'))."','$this->x51_tipoleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x51_numcgm"]) || $this->x51_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2705,16565,'".AddSlashes(pg_result($resaco,$conresaco,'x51_numcgm'))."','$this->x51_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados Leitura nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x51_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados Leitura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x51_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x51_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15406,'$x51_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2705,15406,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,15407,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,15408,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_agualeitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,15409,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_diasultimaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,15410,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_mesesultimaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,15411,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_tipoleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2705,16565,'','".AddSlashes(pg_result($resaco,$iresaco,'x51_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacoletorexportadadosleitura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x51_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x51_sequencial = $x51_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados Leitura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x51_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados Leitura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x51_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacoletorexportadadosleitura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportadadosleitura ";
     $sql .= "      inner join agualeitura  on  agualeitura.x21_codleitura = aguacoletorexportadadosleitura.x51_agualeitura";
     $sql .= "      inner join aguacoletorexportadados  on  aguacoletorexportadados.x50_sequencial = aguacoletorexportadadosleitura.x51_aguacoletorexportadados";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agualeitura.x21_usuario";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      inner join agualeiturista  on  agualeiturista.x16_numcgm = agualeitura.x21_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
     $sql .= "      left  join zonas  on  zonas.j50_zona = aguacoletorexportadados.x50_zona";
     $sql .= "      inner join aguahidromatric  as a on   a.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacoletorexportadados.x50_matric";
     $sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguacoletorexportadados.x50_rota";
     $sql .= "      left  join ruastipo  on  ruastipo.j88_codigo = aguacoletorexportadados.x50_tipo";
     $sql .= "      inner join aguacoletorexporta  as b on   b.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
     $sql .= "      left  join aguacoletorexportadados  as c on   c.x50_sequencial = aguacoletorexportadados.x50_aguacoletorexportadados";
     $sql2 = "";
     if($dbwhere==""){
       if($x51_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadadosleitura.x51_sequencial = $x51_sequencial "; 
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
   function sql_query_file ( $x51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportadadosleitura ";
     $sql2 = "";
     if($dbwhere==""){
       if($x51_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadadosleitura.x51_sequencial = $x51_sequencial "; 
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