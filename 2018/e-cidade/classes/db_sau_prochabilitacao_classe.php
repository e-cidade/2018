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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_prochabilitacao
class cl_sau_prochabilitacao { 
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
   var $sd77_i_codigo = 0; 
   var $sd77_i_procedimento = 0; 
   var $sd77_i_habilitacao = 0; 
   var $sd77_i_grupohabilitacao = 0; 
   var $sd77_i_anocomp = 0; 
   var $sd77_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd77_i_codigo = int8 = C�digo 
                 sd77_i_procedimento = int8 = Procedimento 
                 sd77_i_habilitacao = int8 = Habilita��o 
                 sd77_i_grupohabilitacao = int8 = Grupo Habilitacao 
                 sd77_i_anocomp = int4 = Ano 
                 sd77_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_prochabilitacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_prochabilitacao"); 
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
       $this->sd77_i_codigo = ($this->sd77_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_codigo"]:$this->sd77_i_codigo);
       $this->sd77_i_procedimento = ($this->sd77_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_procedimento"]:$this->sd77_i_procedimento);
       $this->sd77_i_habilitacao = ($this->sd77_i_habilitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_habilitacao"]:$this->sd77_i_habilitacao);
       $this->sd77_i_grupohabilitacao = ($this->sd77_i_grupohabilitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_grupohabilitacao"]:$this->sd77_i_grupohabilitacao);
       $this->sd77_i_anocomp = ($this->sd77_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_anocomp"]:$this->sd77_i_anocomp);
       $this->sd77_i_mescomp = ($this->sd77_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_mescomp"]:$this->sd77_i_mescomp);
     }else{
       $this->sd77_i_codigo = ($this->sd77_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd77_i_codigo"]:$this->sd77_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd77_i_codigo){ 
      $this->atualizacampos();
     if($this->sd77_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd77_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd77_i_habilitacao == null ){ 
       $this->erro_sql = " Campo Habilita��o nao Informado.";
       $this->erro_campo = "sd77_i_habilitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd77_i_grupohabilitacao == null ){ 
       $this->sd77_i_grupohabilitacao = "null";
     }
     if($this->sd77_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd77_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd77_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd77_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd77_i_codigo == "" || $sd77_i_codigo == null ){
       $result = db_query("select nextval('sau_prochabilitacao_sd77_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_prochabilitacao_sd77_i_codigo_seq do campo: sd77_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd77_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_prochabilitacao_sd77_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd77_i_codigo)){
         $this->erro_sql = " Campo sd77_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd77_i_codigo = $sd77_i_codigo; 
       }
     }
     if(($this->sd77_i_codigo == null) || ($this->sd77_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd77_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_prochabilitacao(
                                       sd77_i_codigo 
                                      ,sd77_i_procedimento 
                                      ,sd77_i_habilitacao 
                                      ,sd77_i_grupohabilitacao 
                                      ,sd77_i_anocomp 
                                      ,sd77_i_mescomp 
                       )
                values (
                                $this->sd77_i_codigo 
                               ,$this->sd77_i_procedimento 
                               ,$this->sd77_i_habilitacao 
                               ,$this->sd77_i_grupohabilitacao 
                               ,$this->sd77_i_anocomp 
                               ,$this->sd77_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos e Habilita��es ($this->sd77_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos e Habilita��es j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos e Habilita��es ($this->sd77_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd77_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd77_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11570,'$this->sd77_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2003,11570,'','".AddSlashes(pg_result($resaco,0,'sd77_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2003,11673,'','".AddSlashes(pg_result($resaco,0,'sd77_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2003,11572,'','".AddSlashes(pg_result($resaco,0,'sd77_i_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2003,11573,'','".AddSlashes(pg_result($resaco,0,'sd77_i_grupohabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2003,11574,'','".AddSlashes(pg_result($resaco,0,'sd77_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2003,11575,'','".AddSlashes(pg_result($resaco,0,'sd77_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd77_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_prochabilitacao set ";
     $virgula = "";
     if(trim($this->sd77_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_codigo"])){ 
       $sql  .= $virgula." sd77_i_codigo = $this->sd77_i_codigo ";
       $virgula = ",";
       if(trim($this->sd77_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd77_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd77_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_procedimento"])){ 
       $sql  .= $virgula." sd77_i_procedimento = $this->sd77_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd77_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd77_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd77_i_habilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_habilitacao"])){ 
       $sql  .= $virgula." sd77_i_habilitacao = $this->sd77_i_habilitacao ";
       $virgula = ",";
       if(trim($this->sd77_i_habilitacao) == null ){ 
         $this->erro_sql = " Campo Habilita��o nao Informado.";
         $this->erro_campo = "sd77_i_habilitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd77_i_grupohabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_grupohabilitacao"])){ 
        if(trim($this->sd77_i_grupohabilitacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_grupohabilitacao"])){ 
           $this->sd77_i_grupohabilitacao = "0" ; 
        } 
       $sql  .= $virgula." sd77_i_grupohabilitacao = $this->sd77_i_grupohabilitacao ";
       $virgula = ",";
     }
     if(trim($this->sd77_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_anocomp"])){ 
       $sql  .= $virgula." sd77_i_anocomp = $this->sd77_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd77_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd77_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd77_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_mescomp"])){ 
       $sql  .= $virgula." sd77_i_mescomp = $this->sd77_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd77_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd77_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd77_i_codigo!=null){
       $sql .= " sd77_i_codigo = $this->sd77_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd77_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11570,'$this->sd77_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_codigo"]) || $this->sd77_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2003,11570,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_codigo'))."','$this->sd77_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_procedimento"]) || $this->sd77_i_procedimento != "")
           $resac = db_query("insert into db_acount values($acount,2003,11673,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_procedimento'))."','$this->sd77_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_habilitacao"]) || $this->sd77_i_habilitacao != "")
           $resac = db_query("insert into db_acount values($acount,2003,11572,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_habilitacao'))."','$this->sd77_i_habilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_grupohabilitacao"]) || $this->sd77_i_grupohabilitacao != "")
           $resac = db_query("insert into db_acount values($acount,2003,11573,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_grupohabilitacao'))."','$this->sd77_i_grupohabilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_anocomp"]) || $this->sd77_i_anocomp != "")
           $resac = db_query("insert into db_acount values($acount,2003,11574,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_anocomp'))."','$this->sd77_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd77_i_mescomp"]) || $this->sd77_i_mescomp != "")
           $resac = db_query("insert into db_acount values($acount,2003,11575,'".AddSlashes(pg_result($resaco,$conresaco,'sd77_i_mescomp'))."','$this->sd77_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Habilita��es nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd77_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Habilita��es nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd77_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd77_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd77_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd77_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11570,'$sd77_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2003,11570,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2003,11673,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2003,11572,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2003,11573,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_grupohabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2003,11574,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2003,11575,'','".AddSlashes(pg_result($resaco,$iresaco,'sd77_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_prochabilitacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd77_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd77_i_codigo = $sd77_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Habilita��es nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd77_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Habilita��es nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd77_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd77_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_prochabilitacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd77_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_prochabilitacao ";
     $sql .= "      left  join sau_grupohabilitacao  on  sau_grupohabilitacao.sd76_i_codigo = sau_prochabilitacao.sd77_i_grupohabilitacao";
     $sql .= "      left  join sau_habilitacao  on  sau_habilitacao.sd75_i_codigo = sau_prochabilitacao.sd77_i_habilitacao";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_prochabilitacao.sd77_i_procedimento";
     $sql .= "      left  join sau_habilitacao  as a on   a.sd75_i_codigo = sau_grupohabilitacao.sd76_i_habilitacao";
     $sql .= "      left  join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left  join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      inner join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd77_i_codigo!=null ){
         $sql2 .= " where sau_prochabilitacao.sd77_i_codigo = $sd77_i_codigo "; 
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
   function sql_query_file ( $sd77_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_prochabilitacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd77_i_codigo!=null ){
         $sql2 .= " where sau_prochabilitacao.sd77_i_codigo = $sd77_i_codigo "; 
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