<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE orcparamrelnota
class cl_orcparamrelnota { 
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
   var $o42_codparrel = 0; 
   var $o42_anousu = 0; 
   var $o42_instit = 0; 
   var $o42_nota = null; 
   var $o42_fonte = null; 
   var $o42_periodo = null; 
   var $o42_sequencial = null; 
   var $o42_tamanhofontenota = 0; 
   var $o42_tamanhofontedados = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o42_codparrel = int4 = Código Relatório 
                 o42_anousu = int4 = Exercício 
                 o42_instit = int4 = Instituição 
                 o42_nota = text = Notas Explicativas 
                 o42_fonte = text = Fonte 
                 o42_periodo = char(2) = Periodo 
                 o42_sequencial = char(2) = sequencial 
                 o42_tamanhofontenota = float8 = Tamanho da Fonte 
                 o42_tamanhofontedados = float8 = Tamanho da Fonte
                 ";
   //funcao construtor da classe 
   function cl_orcparamrelnota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamrelnota"); 
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
     if ($exclusao==false) {
       
       $this->o42_codparrel = ($this->o42_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]:$this->o42_codparrel);
       $this->o42_anousu = ($this->o42_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_anousu"]:$this->o42_anousu);
       $this->o42_instit = ($this->o42_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_instit"]:$this->o42_instit);
       $this->o42_nota = ($this->o42_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_nota"]:$this->o42_nota);
       $this->o42_fonte = ($this->o42_fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_fonte"]:$this->o42_fonte);
       $this->o42_periodo = ($this->o42_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_periodo"]:$this->o42_periodo);
       $this->o42_tamanhofontenota = ($this->o42_tamanhofontenota == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontenota"]:$this->o42_tamanhofontenota);
       $this->o42_tamanhofontedados = ($this->o42_tamanhofontedados == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontedados"]:$this->o42_tamanhofontedados);
     }else{
       $this->o42_codparrel = ($this->o42_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]:$this->o42_codparrel);
       $this->o42_anousu = ($this->o42_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_anousu"]:$this->o42_anousu);
       $this->o42_instit = ($this->o42_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_instit"]:$this->o42_instit);
       $this->o42_periodo = ($this->o42_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_periodo"]:$this->o42_periodo);
     }
   }
   // funcao para inclusao
   function incluir ($o42_codparrel,$o42_anousu,$o42_instit,$o42_periodo) {
      
      $this->atualizacampos();
       $this->o42_codparrel = $o42_codparrel; 
       $this->o42_anousu = $o42_anousu; 
       $this->o42_instit = $o42_instit; 
       $this->o42_periodo = $o42_periodo; 
     if(($this->o42_codparrel == null) || ($this->o42_codparrel == "") ){ 
       $this->erro_sql = " Campo o42_codparrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o42_anousu == null) || ($this->o42_anousu == "") ){ 
       $this->erro_sql = " Campo o42_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o42_instit == null) || ($this->o42_instit == "") ){ 
       $this->erro_sql = " Campo o42_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o42_periodo == null) || ($this->o42_periodo == "") ){ 
       $this->erro_sql = " Campo o42_periodo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o42_tamanhofontenota == null ){ 
       $this->erro_sql = " Campo Tamanho da Fonte nao Informado.";
       $this->erro_campo = "o42_tamanhofontenota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o42_tamanhofontedados == null ){ 
       $this->erro_sql = " Campo Tamanho da Fonte nao Informado.";
       $this->erro_campo = "o42_tamanhofontedados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
    if($this->o42_sequencial == "" || $this->o42_sequencial == null ){
       $result = db_query("select nextval('orcparamrelnota_o42_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamrelnota_o42_sequencial_seq do campo: o42_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o42_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamrelnota_o42_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o42_sequencial)){
         $this->erro_sql = " Campo o42_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o42_sequencial = $o42_sequencial; 
       }
     }
     if(($this->o42_sequencial == null) || ($this->o42_sequencial == "") ){ 
       $this->erro_sql = " Campo o42_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     $sqlVerificaNotas  =  $this->sql_query($this->o42_codparrel, $this->o42_anousu, $this->o42_instit , $this->o42_periodo) ;
     $rsNotas = db_query($sqlVerificaNotas);

     // Verifica se existe nota cadastrada anteriormente, caso exista, retorna mensagem
     if($rsNotas) {

       if(pg_numrows($rsNotas) > 0 ) {

          $this->erro_sql = " ($this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo) nao Incluído. Inclusao Abortada.";
          $this->erro_msg = "Notas Explicativas/Fonte já estão cadastradas.";
          return false;
       }
     }

     $sql = "insert into orcparamrelnota(
                                       o42_codparrel 
                                      ,o42_anousu 
                                      ,o42_instit 
                                      ,o42_nota 
                                      ,o42_fonte 
                                      ,o42_periodo
                                      ,o42_sequencial 
                                      ,o42_tamanhofontenota 
                                      ,o42_tamanhofontedados 
                       )
                values (
                                $this->o42_codparrel 
                               ,$this->o42_anousu 
                               ,$this->o42_instit 
                               ,'$this->o42_nota' 
                               ,'$this->o42_fonte' 
                               ,'$this->o42_periodo'
                               ,$this->o42_sequencial  
                               ,$this->o42_tamanhofontenota 
                               ,$this->o42_tamanhofontedados
                      )";
     $result = db_query($sql); 

     if($result==false){ 

       $this->erro_banco      = str_replace("\n","",@pg_last_error());
       $this->erro_sql        = " ($this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo) nao Incluído. Inclusao Abortada.";
       $this->erro_msg        = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg       .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status     = "0";
       $this->numrows_incluir = 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o42_codparrel,$this->o42_anousu,$this->o42_instit,$this->o42_periodo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5705,'$this->o42_codparrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,8619,'$this->o42_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,9431,'$this->o42_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,10998,'$this->o42_periodo','I')");
       $resac = db_query("insert into db_acount values($acount,1468,5705,'','".AddSlashes(pg_result($resaco,0,'o42_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,8619,'','".AddSlashes(pg_result($resaco,0,'o42_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,9431,'','".AddSlashes(pg_result($resaco,0,'o42_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,8620,'','".AddSlashes(pg_result($resaco,0,'o42_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,10999,'','".AddSlashes(pg_result($resaco,0,'o42_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,14166,'','".AddSlashes(pg_result($resaco,0,'o42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,18385,'','".AddSlashes(pg_result($resaco,0,'o42_tamanhofontenota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1468,18386,'','".AddSlashes(pg_result($resaco,0,'o42_tamanhofontedados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o42_codparrel=null,$o42_anousu=null,$o42_instit=null,$o42_periodo=null) { 
      $this->atualizacampos();
     $sql = " update orcparamrelnota set ";
     $virgula = "";
     if(trim($this->o42_codparrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_codparrel"])){ 
       $sql  .= $virgula." o42_codparrel = $this->o42_codparrel ";
       $virgula = ",";
       if(trim($this->o42_codparrel) == null ){ 
         $this->erro_sql = " Campo Código Relatório nao Informado.";
         $this->erro_campo = "o42_codparrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_anousu"])){ 
       $sql  .= $virgula." o42_anousu = $this->o42_anousu ";
       $virgula = ",";
       if(trim($this->o42_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o42_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_instit"])){ 
       $sql  .= $virgula." o42_instit = $this->o42_instit ";
       $virgula = ",";
       if(trim($this->o42_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o42_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_nota"])){ 
       $sql  .= $virgula." o42_nota = '$this->o42_nota' ";
       $virgula = ",";
     }
     if(trim($this->o42_fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_fonte"])){ 
       $sql  .= $virgula." o42_fonte = '$this->o42_fonte' ";
       $virgula = ",";
     }
     if(trim($this->o42_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_periodo"])){ 
       $sql  .= $virgula." o42_periodo = '$this->o42_periodo' ";
       $virgula = ",";
     }
     if(trim($this->o42_tamanhofontenota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontenota"])){ 
       $sql  .= $virgula." o42_tamanhofontenota = $this->o42_tamanhofontenota ";
       $virgula = ",";
       if(trim($this->o42_tamanhofontenota) == null ){ 
         $this->erro_sql = " Campo Tamanho da Fonte nao Informado.";
         $this->erro_campo = "o42_tamanhofontenota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_tamanhofontedados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontedados"])){ 
       $sql  .= $virgula." o42_tamanhofontedados = $this->o42_tamanhofontedados ";
       $virgula = ",";
       if(trim($this->o42_tamanhofontedados) == null ){ 
         $this->erro_sql = " Campo Tamanho da Fonte nao Informado.";
         $this->erro_campo = "o42_tamanhofontedados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o42_codparrel!=null){
       $sql .= " o42_codparrel = $this->o42_codparrel";
     }
     if($o42_anousu!=null){
       $sql .= " and  o42_anousu = $this->o42_anousu";
     }
     if($o42_instit!=null){
       $sql .= " and  o42_instit = $this->o42_instit";
     }
     if($o42_periodo!=null){
       $sql .= " and  o42_periodo = '$this->o42_periodo'";
     }
     
     $resaco = $this->sql_record($this->sql_query_file($this->o42_codparrel,$this->o42_anousu,$this->o42_instit,$this->o42_periodo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5705,'$this->o42_codparrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,8619,'$this->o42_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,9431,'$this->o42_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,10998,'$this->o42_periodo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]))
           $resac = db_query("insert into db_acount values($acount,1468,5705,'".AddSlashes(pg_result($resaco,$conresaco,'o42_codparrel'))."','$this->o42_codparrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1468,8619,'".AddSlashes(pg_result($resaco,$conresaco,'o42_anousu'))."','$this->o42_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_instit"]))
           $resac = db_query("insert into db_acount values($acount,1468,9431,'".AddSlashes(pg_result($resaco,$conresaco,'o42_instit'))."','$this->o42_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_nota"]))
           $resac = db_query("insert into db_acount values($acount,1468,8620,'".AddSlashes(pg_result($resaco,$conresaco,'o42_nota'))."','$this->o42_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_fonte"]))
           $resac = db_query("insert into db_acount values($acount,1468,10999,'".AddSlashes(pg_result($resaco,$conresaco,'o42_fonte'))."','$this->o42_fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_periodo"]))
           $resac = db_query("insert into db_acount values($acount,1468,10998,'".AddSlashes(pg_result($resaco,$conresaco,'o42_periodo'))."','$this->o42_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1468,14166,'".AddSlashes(pg_result($resaco,$conresaco,'o42_sequencial'))."','$this->o42_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontenota"]))
           $resac = db_query("insert into db_acount values($acount,1468,18385,'".AddSlashes(pg_result($resaco,$conresaco,'o42_tamanhofontenota'))."','$this->o42_tamanhofontenota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_tamanhofontedados"]))
           $resac = db_query("insert into db_acount values($acount,1468,18386,'".AddSlashes(pg_result($resaco,$conresaco,'o42_tamanhofontedados'))."','$this->o42_tamanhofontedados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");    
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel."-".$this->o42_anousu."-".$this->o42_instit."-".$this->o42_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o42_codparrel=null,$o42_anousu=null,$o42_instit=null,$o42_periodo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o42_codparrel,$o42_anousu,$o42_instit,$o42_periodo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5705,'$o42_codparrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,8619,'$o42_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,9431,'$o42_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,10998,'$o42_periodo','E')");
         $resac = db_query("insert into db_acount values($acount,1468,5705,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,8619,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,9431,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,8620,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,10999,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,10998,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,14166,'','".AddSlashes(pg_result($resaco,0,'o42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,18385,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_tamanhofontenota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1468,18386,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_tamanhofontedados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamrelnota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o42_codparrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o42_codparrel = $o42_codparrel ";
        }
        if($o42_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o42_anousu = $o42_anousu ";
        }
        if($o42_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o42_instit = $o42_instit ";
        }
        if($o42_periodo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o42_periodo = '$o42_periodo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o42_codparrel."-".$o42_anousu."-".$o42_instit."-".$o42_periodo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o42_codparrel."-".$o42_anousu."-".$o42_instit."-".$o42_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o42_codparrel."-".$o42_anousu."-".$o42_instit."-".$o42_periodo;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamrelnota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o42_codparrel=null,$o42_anousu=null,$o42_instit=null,$o42_periodo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrelnota ";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamrelnota.o42_codparrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o42_codparrel!=null ){
         $sql2 .= " where orcparamrelnota.o42_codparrel = $o42_codparrel "; 
       } 
       if($o42_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_anousu = $o42_anousu "; 
       } 
       if($o42_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_instit = $o42_instit "; 
       } 
       if($o42_periodo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_periodo = '$o42_periodo' "; 
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
   function sql_query_file ( $o42_codparrel=null,$o42_anousu=null,$o42_instit=null,$o42_periodo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrelnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($o42_codparrel!=null ){
         $sql2 .= " where orcparamrelnota.o42_codparrel = $o42_codparrel "; 
       } 
       if($o42_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_anousu = $o42_anousu "; 
       } 
       if($o42_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_instit = $o42_instit "; 
       } 
       if($o42_periodo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_periodo = '$o42_periodo' "; 
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
  
  function sql_query_periodo( $o42_codparrel=null,$o42_anousu=null,$o42_instit=null,$o42_periodo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrelnota ";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamrelnota.o42_codparrel";
     $sql .= "      left join orcparamrelnotaperiodo  on  o42_sequencial = o118_orcparamrelnota";
     $sql .= "      left join periodo on o118_periodo = o114_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($o42_codparrel!=null ){
         $sql2 .= " where orcparamrelnota.o42_codparrel = $o42_codparrel "; 
       } 
       if($o42_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_anousu = $o42_anousu "; 
       } 
       if($o42_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_instit = $o42_instit "; 
       } 
       if($o42_periodo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamrelnota.o42_periodo = '$o42_periodo' "; 
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