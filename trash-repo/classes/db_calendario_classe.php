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

//MODULO: educação
//CLASSE DA ENTIDADE calendario
class cl_calendario { 
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
   var $ed52_i_codigo = 0; 
   var $ed52_c_descr = null; 
   var $ed52_i_duracaocal = 0; 
   var $ed52_i_ano = 0; 
   var $ed52_i_periodo = 0; 
   var $ed52_d_inicio_dia = null; 
   var $ed52_d_inicio_mes = null; 
   var $ed52_d_inicio_ano = null; 
   var $ed52_d_inicio = null; 
   var $ed52_d_fim_dia = null; 
   var $ed52_d_fim_mes = null; 
   var $ed52_d_fim_ano = null; 
   var $ed52_d_fim = null; 
   var $ed52_d_resultfinal_dia = null; 
   var $ed52_d_resultfinal_mes = null; 
   var $ed52_d_resultfinal_ano = null; 
   var $ed52_d_resultfinal = null; 
   var $ed52_c_aulasabado = null; 
   var $ed52_i_diasletivos = 0; 
   var $ed52_i_semletivas = 0; 
   var $ed52_i_calendant = 0; 
   var $ed52_c_passivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed52_i_codigo = int8 = Código 
                 ed52_c_descr = char(20) = Descrição 
                 ed52_i_duracaocal = int8 = Duração 
                 ed52_i_ano = int4 = Ano 
                 ed52_i_periodo = int4 = Período 
                 ed52_d_inicio = date = Data Inicial 
                 ed52_d_fim = date = Data Final 
                 ed52_d_resultfinal = date = Data Resultado Final 
                 ed52_c_aulasabado = char(1) = Aula aos Sábados 
                 ed52_i_diasletivos = int4 = Dias Letivos 
                 ed52_i_semletivas = int4 = Semanas Letivas 
                 ed52_i_calendant = int4 = Calendário Anterior 
                 ed52_c_passivo = char(1) = Passivo 
                 ";
   //funcao construtor da classe 
   function cl_calendario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("calendario"); 
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
       $this->ed52_i_codigo = ($this->ed52_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_codigo"]:$this->ed52_i_codigo);
       $this->ed52_c_descr = ($this->ed52_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_c_descr"]:$this->ed52_c_descr);
       $this->ed52_i_duracaocal = ($this->ed52_i_duracaocal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_duracaocal"]:$this->ed52_i_duracaocal);
       $this->ed52_i_ano = ($this->ed52_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_ano"]:$this->ed52_i_ano);
       $this->ed52_i_periodo = ($this->ed52_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_periodo"]:$this->ed52_i_periodo);
       if($this->ed52_d_inicio == ""){
         $this->ed52_d_inicio_dia = ($this->ed52_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_dia"]:$this->ed52_d_inicio_dia);
         $this->ed52_d_inicio_mes = ($this->ed52_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_mes"]:$this->ed52_d_inicio_mes);
         $this->ed52_d_inicio_ano = ($this->ed52_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_ano"]:$this->ed52_d_inicio_ano);
         if($this->ed52_d_inicio_dia != ""){
            $this->ed52_d_inicio = $this->ed52_d_inicio_ano."-".$this->ed52_d_inicio_mes."-".$this->ed52_d_inicio_dia;
         }
       }
       if($this->ed52_d_fim == ""){
         $this->ed52_d_fim_dia = ($this->ed52_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_dia"]:$this->ed52_d_fim_dia);
         $this->ed52_d_fim_mes = ($this->ed52_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_mes"]:$this->ed52_d_fim_mes);
         $this->ed52_d_fim_ano = ($this->ed52_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_ano"]:$this->ed52_d_fim_ano);
         if($this->ed52_d_fim_dia != ""){
            $this->ed52_d_fim = $this->ed52_d_fim_ano."-".$this->ed52_d_fim_mes."-".$this->ed52_d_fim_dia;
         }
       }
       if($this->ed52_d_resultfinal == ""){
         $this->ed52_d_resultfinal_dia = ($this->ed52_d_resultfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_dia"]:$this->ed52_d_resultfinal_dia);
         $this->ed52_d_resultfinal_mes = ($this->ed52_d_resultfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_mes"]:$this->ed52_d_resultfinal_mes);
         $this->ed52_d_resultfinal_ano = ($this->ed52_d_resultfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_ano"]:$this->ed52_d_resultfinal_ano);
         if($this->ed52_d_resultfinal_dia != ""){
            $this->ed52_d_resultfinal = $this->ed52_d_resultfinal_ano."-".$this->ed52_d_resultfinal_mes."-".$this->ed52_d_resultfinal_dia;
         }
       }
       $this->ed52_c_aulasabado = ($this->ed52_c_aulasabado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_c_aulasabado"]:$this->ed52_c_aulasabado);
       $this->ed52_i_diasletivos = ($this->ed52_i_diasletivos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_diasletivos"]:$this->ed52_i_diasletivos);
       $this->ed52_i_semletivas = ($this->ed52_i_semletivas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_semletivas"]:$this->ed52_i_semletivas);
       $this->ed52_i_calendant = ($this->ed52_i_calendant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_calendant"]:$this->ed52_i_calendant);
       $this->ed52_c_passivo = ($this->ed52_c_passivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_c_passivo"]:$this->ed52_c_passivo);
     }else{
       $this->ed52_i_codigo = ($this->ed52_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed52_i_codigo"]:$this->ed52_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed52_i_codigo){ 
      $this->atualizacampos();
     if($this->ed52_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed52_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_i_duracaocal == null ){ 
       $this->erro_sql = " Campo Duração nao Informado.";
       $this->erro_campo = "ed52_i_duracaocal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_i_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "ed52_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_i_periodo == null ){ 
       $this->ed52_i_periodo = "0";
     }
     if($this->ed52_d_inicio == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ed52_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_d_fim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ed52_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_d_resultfinal == null ){ 
       $this->erro_sql = " Campo Data Resultado Final nao Informado.";
       $this->erro_campo = "ed52_d_resultfinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_c_aulasabado == null ){ 
       $this->erro_sql = " Campo Aula aos Sábados nao Informado.";
       $this->erro_campo = "ed52_c_aulasabado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed52_i_diasletivos == null ){ 
       $this->ed52_i_diasletivos = "0";
     }
     if($this->ed52_i_semletivas == null ){ 
       $this->ed52_i_semletivas = "0";
     }
     if($this->ed52_i_calendant == null ){ 
       $this->ed52_i_calendant = "0";
     }
     if($this->ed52_c_passivo == null ){ 
       $this->erro_sql = " Campo Passivo nao Informado.";
       $this->erro_campo = "ed52_c_passivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed52_i_codigo == "" || $ed52_i_codigo == null ){
       $result = db_query("select nextval('calendario_ed52_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: calendario_ed52_i_codigo_seq do campo: ed52_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed52_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from calendario_ed52_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed52_i_codigo)){
         $this->erro_sql = " Campo ed52_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed52_i_codigo = $ed52_i_codigo; 
       }
     }
     if(($this->ed52_i_codigo == null) || ($this->ed52_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed52_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into calendario(
                                       ed52_i_codigo 
                                      ,ed52_c_descr 
                                      ,ed52_i_duracaocal 
                                      ,ed52_i_ano 
                                      ,ed52_i_periodo 
                                      ,ed52_d_inicio 
                                      ,ed52_d_fim 
                                      ,ed52_d_resultfinal 
                                      ,ed52_c_aulasabado 
                                      ,ed52_i_diasletivos 
                                      ,ed52_i_semletivas 
                                      ,ed52_i_calendant 
                                      ,ed52_c_passivo 
                       )
                values (
                                $this->ed52_i_codigo 
                               ,'$this->ed52_c_descr' 
                               ,$this->ed52_i_duracaocal 
                               ,$this->ed52_i_ano 
                               ,$this->ed52_i_periodo 
                               ,".($this->ed52_d_inicio == "null" || $this->ed52_d_inicio == ""?"null":"'".$this->ed52_d_inicio."'")." 
                               ,".($this->ed52_d_fim == "null" || $this->ed52_d_fim == ""?"null":"'".$this->ed52_d_fim."'")." 
                               ,".($this->ed52_d_resultfinal == "null" || $this->ed52_d_resultfinal == ""?"null":"'".$this->ed52_d_resultfinal."'")." 
                               ,'$this->ed52_c_aulasabado' 
                               ,$this->ed52_i_diasletivos 
                               ,$this->ed52_i_semletivas 
                               ,$this->ed52_i_calendant 
                               ,'$this->ed52_c_passivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calendário Escolar ($this->ed52_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calendário Escolar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calendário Escolar ($this->ed52_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed52_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed52_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008328,'$this->ed52_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010057,1008328,'','".AddSlashes(pg_result($resaco,0,'ed52_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008329,'','".AddSlashes(pg_result($resaco,0,'ed52_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008340,'','".AddSlashes(pg_result($resaco,0,'ed52_i_duracaocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008330,'','".AddSlashes(pg_result($resaco,0,'ed52_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008338,'','".AddSlashes(pg_result($resaco,0,'ed52_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008331,'','".AddSlashes(pg_result($resaco,0,'ed52_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008332,'','".AddSlashes(pg_result($resaco,0,'ed52_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008333,'','".AddSlashes(pg_result($resaco,0,'ed52_d_resultfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008334,'','".AddSlashes(pg_result($resaco,0,'ed52_c_aulasabado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008335,'','".AddSlashes(pg_result($resaco,0,'ed52_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008336,'','".AddSlashes(pg_result($resaco,0,'ed52_i_semletivas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008337,'','".AddSlashes(pg_result($resaco,0,'ed52_i_calendant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010057,1008339,'','".AddSlashes(pg_result($resaco,0,'ed52_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed52_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update calendario set ";
     $virgula = "";
     if(trim($this->ed52_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_codigo"])){ 
       $sql  .= $virgula." ed52_i_codigo = $this->ed52_i_codigo ";
       $virgula = ",";
       if(trim($this->ed52_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed52_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed52_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_descr"])){ 
       $sql  .= $virgula." ed52_c_descr = '$this->ed52_c_descr' ";
       $virgula = ",";
       if(trim($this->ed52_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed52_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed52_i_duracaocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_duracaocal"])){ 
       $sql  .= $virgula." ed52_i_duracaocal = $this->ed52_i_duracaocal ";
       $virgula = ",";
       if(trim($this->ed52_i_duracaocal) == null ){ 
         $this->erro_sql = " Campo Duração nao Informado.";
         $this->erro_campo = "ed52_i_duracaocal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed52_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_ano"])){ 
       $sql  .= $virgula." ed52_i_ano = $this->ed52_i_ano ";
       $virgula = ",";
       if(trim($this->ed52_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ed52_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed52_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_periodo"])){ 
        if(trim($this->ed52_i_periodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_periodo"])){ 
           $this->ed52_i_periodo = "0" ; 
        } 
       $sql  .= $virgula." ed52_i_periodo = $this->ed52_i_periodo ";
       $virgula = ",";
     }
     if(trim($this->ed52_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed52_d_inicio = '$this->ed52_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed52_d_inicio) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ed52_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio_dia"])){ 
         $sql  .= $virgula." ed52_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed52_d_inicio) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ed52_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed52_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." ed52_d_fim = '$this->ed52_d_fim' ";
       $virgula = ",";
       if(trim($this->ed52_d_fim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ed52_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_fim_dia"])){ 
         $sql  .= $virgula." ed52_d_fim = null ";
         $virgula = ",";
         if(trim($this->ed52_d_fim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ed52_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed52_d_resultfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_dia"] !="") ){ 
       $sql  .= $virgula." ed52_d_resultfinal = '$this->ed52_d_resultfinal' ";
       $virgula = ",";
       if(trim($this->ed52_d_resultfinal) == null ){ 
         $this->erro_sql = " Campo Data Resultado Final nao Informado.";
         $this->erro_campo = "ed52_d_resultfinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal_dia"])){ 
         $sql  .= $virgula." ed52_d_resultfinal = null ";
         $virgula = ",";
         if(trim($this->ed52_d_resultfinal) == null ){ 
           $this->erro_sql = " Campo Data Resultado Final nao Informado.";
           $this->erro_campo = "ed52_d_resultfinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed52_c_aulasabado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_aulasabado"])){ 
       $sql  .= $virgula." ed52_c_aulasabado = '$this->ed52_c_aulasabado' ";
       $virgula = ",";
       if(trim($this->ed52_c_aulasabado) == null ){ 
         $this->erro_sql = " Campo Aula aos Sábados nao Informado.";
         $this->erro_campo = "ed52_c_aulasabado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed52_i_diasletivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_diasletivos"])){ 
        if(trim($this->ed52_i_diasletivos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_diasletivos"])){ 
           $this->ed52_i_diasletivos = "0" ; 
        } 
       $sql  .= $virgula." ed52_i_diasletivos = $this->ed52_i_diasletivos ";
       $virgula = ",";
     }
     if(trim($this->ed52_i_semletivas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_semletivas"])){ 
        if(trim($this->ed52_i_semletivas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_semletivas"])){ 
           $this->ed52_i_semletivas = "0" ; 
        } 
       $sql  .= $virgula." ed52_i_semletivas = $this->ed52_i_semletivas ";
       $virgula = ",";
     }
     if(trim($this->ed52_i_calendant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_calendant"])){ 
        if(trim($this->ed52_i_calendant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_calendant"])){ 
           $this->ed52_i_calendant = "0" ; 
        } 
       $sql  .= $virgula." ed52_i_calendant = $this->ed52_i_calendant ";
       $virgula = ",";
     }
     if(trim($this->ed52_c_passivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_passivo"])){ 
       $sql  .= $virgula." ed52_c_passivo = '$this->ed52_c_passivo' ";
       $virgula = ",";
       if(trim($this->ed52_c_passivo) == null ){ 
         $this->erro_sql = " Campo Passivo nao Informado.";
         $this->erro_campo = "ed52_c_passivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed52_i_codigo!=null){
       $sql .= " ed52_i_codigo = $this->ed52_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed52_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008328,'$this->ed52_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008328,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_codigo'))."','$this->ed52_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008329,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_c_descr'))."','$this->ed52_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_duracaocal"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008340,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_duracaocal'))."','$this->ed52_i_duracaocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_ano"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008330,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_ano'))."','$this->ed52_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_periodo"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008338,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_periodo'))."','$this->ed52_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008331,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_d_inicio'))."','$this->ed52_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_fim"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008332,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_d_fim'))."','$this->ed52_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_d_resultfinal"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008333,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_d_resultfinal'))."','$this->ed52_d_resultfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_aulasabado"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008334,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_c_aulasabado'))."','$this->ed52_c_aulasabado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_diasletivos"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008335,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_diasletivos'))."','$this->ed52_i_diasletivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_semletivas"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008336,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_semletivas'))."','$this->ed52_i_semletivas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_i_calendant"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008337,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_i_calendant'))."','$this->ed52_i_calendant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed52_c_passivo"]))
           $resac = db_query("insert into db_acount values($acount,1010057,1008339,'".AddSlashes(pg_result($resaco,$conresaco,'ed52_c_passivo'))."','$this->ed52_c_passivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calendário Escolar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed52_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calendário Escolar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed52_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed52_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed52_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed52_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008328,'$ed52_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010057,1008328,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008329,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008340,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_duracaocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008330,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008338,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008331,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008332,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008333,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_d_resultfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008334,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_c_aulasabado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008335,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008336,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_semletivas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008337,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_i_calendant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010057,1008339,'','".AddSlashes(pg_result($resaco,$iresaco,'ed52_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from calendario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed52_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed52_i_codigo = $ed52_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calendário Escolar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed52_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calendário Escolar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed52_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed52_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:calendario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed52_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from calendario ";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql2 = "";
     if($dbwhere==""){
       if($ed52_i_codigo!=null ){
         $sql2 .= " where calendario.ed52_i_codigo = $ed52_i_codigo ";
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
   function sql_query_calescola ( $ed52_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from calendario ";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql .= "      inner join calendarioescola  on  calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed52_i_codigo!=null ){
         $sql2 .= " where calendario.ed52_i_codigo = $ed52_i_codigo ";
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
   function sql_query_calturma ( $ed52_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select distinct ";
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
     $sql .= " from calendario ";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql .= "      inner join calendarioescola  on  calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo";
     $sql .= "      inner join periodocalendario  on  periodocalendario.ed53_i_calendario = calendario.ed52_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed52_i_codigo!=null ){
         $sql2 .= " where calendario.ed52_i_codigo = $ed52_i_codigo ";
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
   function sql_query_file ( $ed52_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from calendario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed52_i_codigo!=null ){
         $sql2 .= " where calendario.ed52_i_codigo = $ed52_i_codigo "; 
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
  
   function sql_query_calendariorelatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= ' from calendario ';
    $sSql .= '   inner join calendarioescola on ed38_i_calendario = ed52_i_codigo';    
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where calendario.ed52_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  
  function sql_query_diarioclasse($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
  
    $sSql = 'select ';
    if ($sCampos != '*') {
  
      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){
  
        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
  
      }
  
    } else {
      $sSql .= $sCampos;
    }
  
    $sSql .= ' from calendario ';
    $sSql .= '   inner join turma            on ed57_i_calendario = ed52_i_codigo';
    $sSql .= '   inner join matricula        on ed60_i_turma = ed57_i_codigo';
    $sSql .= '   inner join calendarioescola on ed38_i_calendario = ed52_i_codigo';
    $sSql2 = '';
    if ($sDbWhere == '') {
  
      if ($iCodigo != null ){
        $sSql2 .= " where calendario.ed52_i_codigo = $iCodigo ";
      }
  
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
  
    if ($sOrdem != null) {
  
      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {
  
        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';
  
      }
  
    }
  
    return $sSql;
  }
  
  function sql_query_calendariobase($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
  
    $sSql = 'select ';
    if ($sCampos != '*') {
  
      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){
  
        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
  
      }
  
    } else {
      $sSql .= $sCampos;
    }
  
    $sSql .= '  from calendario ';
    $sSql .= " inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
    $sSql .= '  left join calendarioescola on ed38_i_calendario = ed52_i_codigo';
    $sSql2 = '';
    if ($sDbWhere == '') {
  
      if ($iCodigo != null ){
        $sSql2 .= " where calendario.ed52_i_codigo = $iCodigo ";
      }
  
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
  
    if ($sOrdem != null) {
  
      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {
  
        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';
  
      }
  
    }
    return $sSql;
  }
  
}
?>